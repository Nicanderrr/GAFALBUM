<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\UploadedFile;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Mail;
use App\Mail\UserCreatedMail;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('is_admin', false)->latest()->paginate(10);
        $missingPasswordCount = $this->missingPasswordUsersQuery()->count();

        return view('admin.users.index', compact('users', 'missingPasswordCount'));
    }

    public function create()
    {
        return view('admin.users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users'],
            'service_number' => ['required', 'string', 'max:255', 'unique:users'],
        ]);

        $password = Str::password(12);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->filled('email') ? $request->email : null,
            'service_number' => $request->service_number,
            'password' => Hash::make($password),
        ]);

        if ($user->email) {
            Mail::to($user->email)->send(new UserCreatedMail($user, $password));
        }

        return redirect()->route('admin.users.index')->with('success', $user->email
            ? 'User created successfully and email sent.'
            : 'User created successfully.');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'string', 'email', 'max:255', 'unique:users,email,'.$user->id],
            'service_number' => ['required', 'string', 'max:255', 'unique:users,service_number,'.$user->id],
        ]);

        $user->update([
            'name' => $request->name,
            'email' => $request->filled('email') ? $request->email : null,
            'service_number' => $request->service_number,
        ]);

        return redirect()->route('admin.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'User deleted successfully.');
    }

    public function assignServiceNumberPasswords(): RedirectResponse
    {
        $users = $this->missingPasswordUsersQuery()->get();

        foreach ($users as $user) {
            $user->password = $user->service_number;
            $user->save();
        }

        $count = $users->count();
        $message = $count > 0
            ? "{$count} user password(s) were assigned from service numbers."
            : 'No imported users without passwords were found.';

        return redirect()->route('admin.users.index')->with('success', $message);
    }

    public function importTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Users Import');
        $sheet->fromArray([
            ['name', 'service_number'],
            ['Kwame Mensah', 'SN1024'],
        ]);

        $sheet->getStyle('A1:C1')->getFont()->setBold(true);
        foreach (range('A', 'C') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $tempPath = tempnam(sys_get_temp_dir(), 'gaf-users-template-');
        (new Xlsx($spreadsheet))->save($tempPath);

        return response()->download(
            $tempPath,
            'gafalbum-users-import-template.xlsx',
            ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
        )->deleteFileAfterSend(true);
    }

    public function import(Request $request): RedirectResponse
    {
        $request->validate([
            'import_file' => 'required|file|mimes:xlsx,xls,csv,txt|max:10240',
        ]);

        $report = $this->importUsersSpreadsheet($request->file('import_file'));

        $message = "{$report['imported']} user(s) created successfully.";
        if ($report['skipped'] > 0) {
            $message .= " {$report['skipped']} row(s) were skipped.";
        }

        return redirect()
            ->route('admin.users.index')
            ->with('success', $message)
            ->with('import_report', $report);
    }

    protected function importUsersSpreadsheet(UploadedFile $file): array
    {
        $spreadsheet = IOFactory::load($file->getRealPath());
        $rows = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        if (count($rows) < 2) {
            return [
                'imported' => 0,
                'skipped' => 0,
                'errors' => ['The spreadsheet has no data rows.'],
            ];
        }

        $headers = collect(array_shift($rows))
            ->mapWithKeys(fn ($value, $column) => [$column => Str::of((string) $value)->trim()->lower()->replace([' ', '-'], '_')->toString()])
            ->all();

        $imported = 0;
        $skipped = 0;
        $errors = [];

        foreach ($rows as $sheetRowNumber => $row) {
            $rowNumber = $sheetRowNumber;
            $payload = [];

            foreach ($row as $column => $value) {
                $header = $headers[$column] ?? null;
                if ($header) {
                    $payload[$header] = trim((string) $value);
                }
            }

            if (collect($payload)->every(fn ($value) => $value === '')) {
                continue;
            }

            $rowErrors = $this->validateImportRow($payload, $rowNumber);
            if ($rowErrors !== []) {
                $skipped++;
                $errors = [...$errors, ...$rowErrors];
                continue;
            }

            $password = Str::password(12);
            $user = User::create([
                'name' => $payload['name'],
                'email' => $this->resolveImportedEmail($payload),
                'service_number' => $payload['service_number'],
                'password' => Hash::make($password),
            ]);

            if ($user->email) {
                Mail::to($user->email)->send(new UserCreatedMail($user, $password));
            }
            $imported++;
        }

        return compact('imported', 'skipped', 'errors');
    }

    protected function validateImportRow(array $payload, int $rowNumber): array
    {
        $errors = [];
        $name = trim((string) ($payload['name'] ?? ''));
        $email = trim((string) ($payload['email'] ?? ''));
        $serviceNumber = trim((string) ($payload['service_number'] ?? ''));

        if ($name === '') {
            $errors[] = "Row {$rowNumber}: name is required.";
        }

        if ($email !== '' && ! filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors[] = "Row {$rowNumber}: email is not valid.";
        } elseif ($email !== '' && User::where('email', $email)->exists()) {
            $errors[] = "Row {$rowNumber}: email already exists.";
        }

        if ($serviceNumber === '') {
            $errors[] = "Row {$rowNumber}: service_number is required.";
        } elseif (User::where('service_number', $serviceNumber)->exists()) {
            $errors[] = "Row {$rowNumber}: service_number already exists.";
        }

        return $errors;
    }

    protected function resolveImportedEmail(array $payload): ?string
    {
        $email = trim((string) ($payload['email'] ?? ''));

        return $email !== '' ? $email : null;
    }

    protected function missingPasswordUsersQuery(): Builder
    {
        return User::query()
            ->where('is_admin', false)
            ->where(function ($query) {
                $query->whereNull('password')->orWhere('password', '');
            });
    }
}
