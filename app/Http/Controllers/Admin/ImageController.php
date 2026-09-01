<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Image;
use App\Models\ImageMedia;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ImageController extends Controller
{
    public function index(Request $request)
    {
        $filters = [
            'search' => trim((string) $request->string('search')),
            'status' => $request->string('status')->toString(),
            'category_id' => $request->integer('category_id') ?: null,
        ];

        $imagesQuery = Image::query()
            ->with(['category', 'coverMedia', 'defaultCoverMedia'])
            ->withCount('media');

        if ($filters['search'] !== '') {
            $search = $filters['search'];
            $imagesQuery->where(function ($query) use ($search) {
                $query
                    ->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('category', fn ($categoryQuery) => $categoryQuery->where('name', 'like', "%{$search}%"));
            });
        }

        if (in_array($filters['status'], ['draft', 'published', 'archived'], true)) {
            $imagesQuery->where('status', $filters['status']);
        }

        if ($filters['category_id']) {
            $imagesQuery->where('category_id', $filters['category_id']);
        }

        $images = $imagesQuery->latest()->paginate(12)->withQueryString();
        $categories = Category::orderBy('name')->get();
        $summary = [
            'all' => Image::count(),
            'published' => Image::published()->count(),
            'draft' => Image::draft()->count(),
            'archived' => Image::archived()->count(),
            'media' => ImageMedia::count(),
        ];

        return view('admin.images.index', compact('images', 'categories', 'filters', 'summary'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.images.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->merge([
            'status' => $request->input('status', 'published'),
        ]);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'status' => ['required', Rule::in(['draft', 'published', 'archived'])],
            'media' => 'required|array|min:1',
            'media.*' => 'required|file|mimes:jpg,jpeg,png,webp,gif,mp4,mov,avi,webm|max:51200',
        ]);

        $files = $request->file('media');

        if (! str_starts_with($files[0]->getMimeType(), 'image/')) {
            return back()
                ->withErrors(['media' => 'The first selected file must be an image because it is used as the event thumbnail.'])
                ->withInput();
        }

        $paths = [];

        foreach ($files as $file) {
            $paths[] = [
                'path' => $file->store('images', 'public'),
                'type' => str_starts_with($file->getMimeType(), 'video/') ? 'video' : 'image',
            ];
        }

        $image = Image::create([
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'status' => $request->status,
            'file_path' => $paths[0]['path'],
            'thumbnail_path' => $paths[0]['path'],
            'admin_id' => auth()->id(),
            'published_at' => $request->status === 'published' ? now() : null,
        ]);

        foreach ($paths as $index => $media) {
            $image->media()->create([
                'file_path' => $media['path'],
                'media_type' => $media['type'],
                'sort_order' => $index,
            ]);
        }

        $coverMedia = $image->media()->where('media_type', 'image')->orderBy('sort_order')->first();

        if ($coverMedia) {
            $image->update([
                'cover_media_id' => $coverMedia->id,
                'file_path' => $coverMedia->file_path,
                'thumbnail_path' => $coverMedia->file_path,
            ]);
        }

        return redirect()->route('admin.images.index')->with('success', 'Event uploaded successfully.');
    }

    public function show($id)
    {
        // Add show logic later
    }

    public function edit($id)
    {
        $image = Image::with(['category', 'media', 'coverMedia'])->findOrFail($id);
        $categories = Category::orderBy('name')->get();

        return view('admin.images.edit', compact('image', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $image = Image::with(['media', 'coverMedia'])->findOrFail($id);

        if ($request->filled('thumbnail_media_id') && ! $request->filled('cover_media_id')) {
            $request->merge([
                'cover_media_id' => $request->input('thumbnail_media_id'),
            ]);
        }

        $request->merge([
            'status' => $request->input('status', $image->status ?: 'published'),
        ]);

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'category_id' => 'nullable|exists:categories,id',
            'price' => 'required|numeric|min:0',
            'status' => ['required', Rule::in(['draft', 'published', 'archived'])],
            'media' => 'nullable|array',
            'media.*' => 'file|mimes:jpg,jpeg,png,webp,gif,mp4,mov,avi,webm|max:51200',
            'cover_media_id' => [
                'nullable',
                Rule::exists('image_media', 'id')->where(fn ($query) => $query->where('image_id', $image->id)),
            ],
            'remove_media_ids' => 'nullable|array',
            'remove_media_ids.*' => [
                'integer',
                Rule::exists('image_media', 'id')->where(fn ($query) => $query->where('image_id', $image->id)),
            ],
        ]);

        $image->update([
            'title' => $request->title,
            'description' => $request->description,
            'category_id' => $request->category_id,
            'price' => $request->price,
            'status' => $request->status,
            'published_at' => $request->status === 'published'
                ? ($image->published_at ?? now())
                : null,
        ]);

        $removeMediaIds = collect($request->input('remove_media_ids', []))
            ->filter()
            ->map(fn ($id) => (int) $id)
            ->values();

        if ($removeMediaIds->isNotEmpty()) {
            foreach ($image->media->whereIn('id', $removeMediaIds) as $media) {
                Storage::disk('public')->delete($media->file_path);
                $media->delete();
            }
        }

        if ($request->hasFile('media')) {
            $lastSortOrder = (int) $image->media()->max('sort_order');

            foreach ($request->file('media') as $index => $file) {
                $image->media()->create([
                    'file_path' => $file->store('images', 'public'),
                    'media_type' => str_starts_with($file->getMimeType(), 'video/') ? 'video' : 'image',
                    'sort_order' => $lastSortOrder + $index + 1,
                ]);
            }
        }

        $image->load(['media', 'coverMedia']);

        if ($image->media->isEmpty()) {
            return back()
                ->withErrors(['media' => 'Each event must keep at least one file.'])
                ->withInput();
        }

        if (! $image->media->contains(fn ($media) => $media->media_type === 'image')) {
            return back()
                ->withErrors(['media' => 'Each event must keep at least one image file for the thumbnail.'])
                ->withInput();
        }

        $cover = $request->cover_media_id
            ? $image->media->firstWhere('id', (int) $request->cover_media_id)
            : ($image->coverMedia && $image->media->contains('id', $image->coverMedia->id)
                ? $image->coverMedia
                : $image->media->firstWhere('media_type', 'image'));

        if ($cover && $cover->media_type !== 'image') {
            return back()
                ->withErrors(['cover_media_id' => 'The event thumbnail must be an image file.'])
                ->withInput();
        }

        if ($cover) {
            $image->update([
                'cover_media_id' => $cover->id,
                'file_path' => $cover->file_path,
                'thumbnail_path' => $cover->file_path,
            ]);
        }

        return redirect()->route('admin.images.index')->with('success', 'Event updated successfully.');
    }

    public function destroy($id)
    {
        $image = Image::with('media')->findOrFail($id);

        foreach ($image->media as $media) {
            Storage::disk('public')->delete($media->file_path);
        }

        if ($image->media->isEmpty()) {
            Storage::disk('public')->delete($image->file_path);
        }

        $image->delete();

        return redirect()->route('admin.images.index')->with('success', 'Event deleted successfully.');
    }

    public function importTemplate()
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Events Import');
        $sheet->fromArray([
            ['title', 'description', 'category', 'price', 'status', 'media_paths', 'cover_media_path'],
            [
                'Passing Out Parade 2026',
                'Graduation ceremony highlights and official parade moments.',
                'Ceremonies',
                '35.00',
                'published',
                'images/parade-01.jpg|images/parade-02.jpg|images/parade-clip-01.mp4',
                'images/parade-01.jpg',
            ],
        ]);

        $sheet->getStyle('A1:G1')->getFont()->setBold(true);
        foreach (range('A', 'G') as $column) {
            $sheet->getColumnDimension($column)->setAutoSize(true);
        }

        $tempPath = tempnam(sys_get_temp_dir(), 'gaf-import-template-');
        $writer = new Xlsx($spreadsheet);
        $writer->save($tempPath);

        return response()->download(
            $tempPath,
            'gafalbum-events-import-template.xlsx',
            ['Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet']
        )->deleteFileAfterSend(true);
    }

    public function import(Request $request)
    {
        $request->validate([
            'import_file' => 'required|file|mimes:xlsx,xls,csv,txt|max:20480',
        ]);

        $result = $this->importSpreadsheet($request->file('import_file'));

        $message = "{$result['imported']} event(s) imported successfully.";
        if ($result['skipped'] > 0) {
            $message .= " {$result['skipped']} row(s) were skipped.";
        }

        return redirect()
            ->route('admin.images.index')
            ->with('success', $message)
            ->with('import_report', $result);
    }

    protected function importSpreadsheet(UploadedFile $file): array
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

        $headers = $this->normalizeImportHeaders(array_shift($rows));
        $imported = 0;
        $skipped = 0;
        $errors = [];

        foreach ($rows as $sheetRowNumber => $row) {
            $rowNumber = $sheetRowNumber;
            $payload = $this->mapImportRow($headers, $row);

            if ($this->rowIsEmpty($payload)) {
                continue;
            }

            $validation = $this->validateImportRow($payload, $rowNumber);
            if ($validation['errors']) {
                $skipped++;
                $errors[] = implode(' ', $validation['errors']);
                continue;
            }

            DB::transaction(function () use ($validation) {
                $this->createImportedImage($validation['data']);
            });

            $imported++;
        }

        return compact('imported', 'skipped', 'errors');
    }

    protected function normalizeImportHeaders(array $headerRow): array
    {
        return collect($headerRow)
            ->mapWithKeys(fn ($value, $column) => [$column => Str::of((string) $value)->trim()->lower()->replace([' ', '-'], '_')->toString()])
            ->all();
    }

    protected function mapImportRow(array $headers, array $row): array
    {
        $mapped = [];

        foreach ($row as $column => $value) {
            $header = $headers[$column] ?? null;
            if ($header) {
                $mapped[$header] = is_string($value) ? trim($value) : $value;
            }
        }

        return $mapped;
    }

    protected function rowIsEmpty(array $payload): bool
    {
        return collect($payload)->every(fn ($value) => $value === null || $value === '');
    }

    protected function validateImportRow(array $payload, int $rowNumber): array
    {
        $errors = [];
        $title = trim((string) ($payload['title'] ?? ''));
        $description = trim((string) ($payload['description'] ?? '')) ?: null;
        $categoryName = trim((string) ($payload['category'] ?? ''));
        $status = Str::lower(trim((string) ($payload['status'] ?? 'published')));
        $price = $payload['price'] ?? null;
        $mediaPaths = $this->parseMediaPaths($payload['media_paths'] ?? '');
        $coverPath = $this->normalizeImportMediaPath($payload['cover_media_path'] ?? '');

        if ($title === '') {
            $errors[] = "Row {$rowNumber}: title is required.";
        }

        if (! is_numeric($price) || (float) $price < 0) {
            $errors[] = "Row {$rowNumber}: price must be a valid non-negative number.";
        }

        if (! in_array($status, ['draft', 'published', 'archived'], true)) {
            $errors[] = "Row {$rowNumber}: status must be draft, published, or archived.";
        }

        if ($mediaPaths->isEmpty()) {
            $errors[] = "Row {$rowNumber}: media_paths must contain at least one file path.";
        }

        if ($mediaPaths->isNotEmpty()) {
            $missing = $mediaPaths->filter(fn ($path) => ! Storage::disk('public')->exists($path))->values();
            if ($missing->isNotEmpty()) {
                $errors[] = "Row {$rowNumber}: these media files were not found on the public disk: ".$missing->implode(', ').'.';
            }
        }

        $typedMedia = $mediaPaths->map(function ($path) {
            return [
                'path' => $path,
                'type' => $this->detectMediaType($path),
            ];
        })->values();

        $imageMedia = $typedMedia->where('type', 'image')->values();
        if ($imageMedia->isEmpty()) {
            $errors[] = "Row {$rowNumber}: at least one referenced media file must be an image for the thumbnail.";
        }

        if ($coverPath !== '') {
            if (! $mediaPaths->contains($coverPath)) {
                $errors[] = "Row {$rowNumber}: cover_media_path must match one of the values in media_paths.";
            } elseif ($this->detectMediaType($coverPath) !== 'image') {
                $errors[] = "Row {$rowNumber}: cover_media_path must point to an image file.";
            }
        }

        return [
            'errors' => $errors,
            'data' => [
                'title' => $title,
                'description' => $description,
                'category_name' => $categoryName,
                'price' => (float) $price,
                'status' => $status,
                'media' => $typedMedia,
                'cover_path' => $coverPath !== '' ? $coverPath : ($imageMedia->first()['path'] ?? null),
            ],
        ];
    }

    protected function parseMediaPaths(mixed $value): Collection
    {
        return collect(preg_split('/[\r\n|,]+/', (string) $value) ?: [])
            ->map(fn ($path) => $this->normalizeImportMediaPath($path))
            ->filter()
            ->values();
    }

    protected function normalizeImportMediaPath(mixed $path): string
    {
        $path = trim((string) $path);

        if ($path === '') {
            return '';
        }

        $path = str_replace('\\', '/', $path);

        if (Str::startsWith($path, url('/storage/'))) {
            $path = Str::after($path, url('/storage/'));
        } elseif (Str::startsWith($path, ['/storage/', 'storage/'])) {
            $path = ltrim(Str::after($path, 'storage/'), '/');
        } else {
            $publicRoot = str_replace('\\', '/', storage_path('app/public/'));
            if (Str::startsWith($path, $publicRoot)) {
                $path = ltrim(Str::after($path, $publicRoot), '/');
            }
        }

        return ltrim($path, '/');
    }

    protected function detectMediaType(string $path): string
    {
        $extension = Str::lower(pathinfo($path, PATHINFO_EXTENSION));

        return in_array($extension, ['mp4', 'mov', 'avi', 'webm'], true) ? 'video' : 'image';
    }

    protected function createImportedImage(array $data): Image
    {
        $categoryId = null;
        if ($data['category_name'] !== '') {
            $category = Category::firstOrCreate(
                ['slug' => Str::slug($data['category_name']) ?: Str::lower(Str::random(8))],
                ['name' => $data['category_name']]
            );

            if ($category->name !== $data['category_name']) {
                $category->update(['name' => $data['category_name']]);
            }

            $categoryId = $category->id;
        }

        $image = Image::create([
            'title' => $data['title'],
            'description' => $data['description'],
            'category_id' => $categoryId,
            'price' => $data['price'],
            'status' => $data['status'],
            'file_path' => $data['cover_path'],
            'thumbnail_path' => $data['cover_path'],
            'admin_id' => auth()->id(),
            'published_at' => $data['status'] === 'published' ? now() : null,
        ]);

        foreach ($data['media'] as $index => $media) {
            $createdMedia = $image->media()->create([
                'file_path' => $media['path'],
                'media_type' => $media['type'],
                'sort_order' => $index,
            ]);

            if ($media['path'] === $data['cover_path']) {
                $image->cover_media_id = $createdMedia->id;
            }
        }

        $image->save();

        return $image;
    }
}
