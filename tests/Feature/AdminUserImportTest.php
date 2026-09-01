<?php

namespace Tests\Feature;

use App\Mail\UserCreatedMail;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Tests\TestCase;

class AdminUserImportTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_user_index_shows_edit_and_delete_actions(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $user = User::factory()->create([
            'name' => 'Editable User',
            'service_number' => 'SN8801',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.users.index'));

        $response->assertOk();
        $response->assertSee(route('admin.users.edit', $user), false);
        $response->assertSee(route('admin.users.destroy', $user), false);
        $response->assertSee('Edit');
        $response->assertSee('Delete');
    }

    public function test_admin_can_download_user_import_template(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.users.import.template'));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    public function test_admin_can_import_users_from_excel(): void
    {
        Mail::fake();

        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $spreadsheetPath = $this->makeSpreadsheet([
            ['name', 'service_number', 'email'],
            ['Ama Boateng', 'SN9901', 'ama.boateng@example.com'],
            ['Kojo Asare', 'SN9902', 'kojo.asare@example.com'],
        ]);

        $response = $this->actingAs($admin)->post(route('admin.users.import'), [
            'import_file' => new UploadedFile(
                $spreadsheetPath,
                'users-import.xlsx',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                null,
                true
            ),
        ]);

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('import_report');

        $this->assertDatabaseHas('users', [
            'email' => 'ama.boateng@example.com',
            'service_number' => 'SN9901',
            'is_admin' => false,
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'kojo.asare@example.com',
            'service_number' => 'SN9902',
            'is_admin' => false,
        ]);

        Mail::assertSent(UserCreatedMail::class, 2);

        @unlink($spreadsheetPath);
    }

    public function test_admin_import_skips_duplicate_user_rows(): void
    {
        Mail::fake();

        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        User::factory()->create([
            'email' => 'existing.user@example.com',
            'service_number' => 'SN1200',
        ]);

        $spreadsheetPath = $this->makeSpreadsheet([
            ['name', 'service_number', 'email'],
            ['Existing User', 'SN1201', 'existing.user@example.com'],
            ['Second Existing', 'SN1200', 'new.person@example.com'],
        ]);

        $response = $this->actingAs($admin)->post(route('admin.users.import'), [
            'import_file' => new UploadedFile(
                $spreadsheetPath,
                'users-import.xlsx',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                null,
                true
            ),
        ]);

        $response->assertRedirect(route('admin.users.index'));

        $report = session('import_report');

        $this->assertSame(0, $report['imported']);
        $this->assertSame(2, $report['skipped']);
        $this->assertCount(2, $report['errors']);
        Mail::assertNothingSent();

        @unlink($spreadsheetPath);
    }

    public function test_admin_can_import_users_without_email(): void
    {
        Mail::fake();

        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $spreadsheetPath = $this->makeSpreadsheet([
            ['name', 'service_number'],
            ['Yaw Mensimah', 'SN5510'],
        ]);

        $response = $this->actingAs($admin)->post(route('admin.users.import'), [
            'import_file' => new UploadedFile(
                $spreadsheetPath,
                'users-import.xlsx',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                null,
                true
            ),
        ]);

        $response->assertRedirect(route('admin.users.index'));

        $this->assertDatabaseHas('users', [
            'name' => 'Yaw Mensimah',
            'service_number' => 'SN5510',
            'email' => null,
            'is_admin' => false,
        ]);

        Mail::assertNothingSent();

        @unlink($spreadsheetPath);
    }

    public function test_admin_can_assign_service_number_passwords_to_users_with_missing_passwords(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $missingPasswordUser = User::factory()->create([
            'email' => 'missing.password@example.com',
            'service_number' => 'SN7001',
        ]);

        $secondMissingPasswordUser = User::factory()->create([
            'email' => 'second.missing@example.com',
            'service_number' => 'SN7002',
        ]);

        $existingPasswordUser = User::factory()->create([
            'email' => 'existing.password@example.com',
            'service_number' => 'SN7003',
            'password' => 'existing-password',
        ]);

        DB::table('users')->whereIn('id', [$missingPasswordUser->id, $secondMissingPasswordUser->id])->update([
            'password' => '',
        ]);

        $response = $this->actingAs($admin)->post(route('admin.users.assign-service-number-passwords'));

        $response->assertRedirect(route('admin.users.index'));
        $response->assertSessionHas('success', '2 user password(s) were assigned from service numbers.');

        $this->assertTrue(Hash::check('SN7001', $missingPasswordUser->fresh()->password));
        $this->assertTrue(Hash::check('SN7002', $secondMissingPasswordUser->fresh()->password));
        $this->assertFalse(Hash::check('SN7003', $existingPasswordUser->fresh()->password));
    }

    protected function makeSpreadsheet(array $rows): string
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getActiveSheet()->fromArray($rows);

        $path = tempnam(sys_get_temp_dir(), 'gaf-users-import-');
        (new Xlsx($spreadsheet))->save($path);

        return $path;
    }
}
