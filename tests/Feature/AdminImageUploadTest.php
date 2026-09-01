<?php

namespace Tests\Feature;

use App\Models\Image;
use App\Models\ImageMedia;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Tests\TestCase;

class AdminImageUploadTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_render_event_create_screen(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.images.create'))
            ->assertOk()
            ->assertSee('Create Event')
            ->assertSee('value="published" checked', false);
    }

    public function test_admin_can_upload_multiple_files_for_one_event(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this->actingAs($admin)->post(route('admin.images.store'), [
            'title' => 'Graduation Parade',
            'price' => 25,
            'media' => [
                UploadedFile::fake()->image('cover.jpg'),
                UploadedFile::fake()->image('second.jpg'),
                UploadedFile::fake()->image('third.jpg'),
            ],
        ]);

        $response->assertRedirect(route('admin.images.index'));

        $event = Image::with('media')->firstOrFail();

        $this->assertSame('Graduation Parade', $event->title);
        $this->assertCount(3, $event->media);
        $this->assertSame($event->media[0]->file_path, $event->file_path);
        $this->assertSame($event->media[0]->file_path, $event->thumbnail_path);
    }

    public function test_admin_can_render_and_update_event_edit_screen(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $event = Image::create([
            'title' => 'Old Event',
            'price' => 10,
            'file_path' => 'images/cover.jpg',
            'thumbnail_path' => 'images/cover.jpg',
            'admin_id' => $admin->id,
        ]);

        $cover = ImageMedia::create([
            'image_id' => $event->id,
            'file_path' => 'images/cover.jpg',
            'media_type' => 'image',
            'sort_order' => 0,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.images.edit', $event))
            ->assertOk()
            ->assertSee('Edit Event')
            ->assertSee('Old Event');

        $response = $this->actingAs($admin)->put(route('admin.images.update', $event), [
            'title' => 'Updated Event',
            'price' => 35,
            'thumbnail_media_id' => $cover->id,
            'media' => [
                UploadedFile::fake()->image('extra.jpg'),
            ],
        ]);

        $response->assertRedirect(route('admin.images.index'));

        $event->refresh();

        $this->assertSame('Updated Event', $event->title);
        $this->assertEquals(35, $event->price);
        $this->assertSame('images/cover.jpg', $event->thumbnail_path);
        $this->assertCount(2, $event->media);
    }

    public function test_admin_can_import_events_from_excel(): void
    {
        Storage::fake('public');

        Storage::disk('public')->put('images/parade-01.jpg', 'fake-image-1');
        Storage::disk('public')->put('images/parade-02.jpg', 'fake-image-2');
        Storage::disk('public')->put('images/parade-clip-01.mp4', 'fake-video');

        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $spreadsheetPath = $this->makeImportSpreadsheet([
            ['title', 'description', 'category', 'price', 'status', 'media_paths', 'cover_media_path'],
            ['Passing Out Parade 2026', 'Graduation ceremony highlights.', 'Ceremonies', '35.00', 'published', 'images/parade-01.jpg|images/parade-02.jpg|images/parade-clip-01.mp4', 'images/parade-02.jpg'],
        ]);

        $response = $this->actingAs($admin)->post(route('admin.images.import'), [
            'import_file' => new UploadedFile(
                $spreadsheetPath,
                'events-import.xlsx',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                null,
                true
            ),
        ]);

        $response->assertRedirect(route('admin.images.index'));
        $response->assertSessionHas('import_report');

        $event = Image::with(['media', 'category', 'coverMedia'])->firstOrFail();

        $this->assertSame('Passing Out Parade 2026', $event->title);
        $this->assertSame('published', $event->status);
        $this->assertSame('Ceremonies', $event->category?->name);
        $this->assertCount(3, $event->media);
        $this->assertSame('images/parade-02.jpg', $event->thumbnail_path);
        $this->assertSame('images/parade-02.jpg', $event->coverMedia?->file_path);

        @unlink($spreadsheetPath);
    }

    public function test_admin_import_skips_rows_with_missing_media(): void
    {
        Storage::fake('public');
        Storage::disk('public')->put('images/existing-cover.jpg', 'fake-image');

        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $spreadsheetPath = $this->makeImportSpreadsheet([
            ['title', 'description', 'category', 'price', 'status', 'media_paths', 'cover_media_path'],
            ['Broken Row', 'This row references a missing file.', 'Training', '20.00', 'draft', 'images/existing-cover.jpg|images/missing-file.jpg', 'images/existing-cover.jpg'],
        ]);

        $response = $this->actingAs($admin)->post(route('admin.images.import'), [
            'import_file' => new UploadedFile(
                $spreadsheetPath,
                'events-import.xlsx',
                'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                null,
                true
            ),
        ]);

        $response->assertRedirect(route('admin.images.index'));
        $response->assertSessionHas('import_report');

        $report = session('import_report');

        $this->assertSame(0, Image::count());
        $this->assertSame(0, $report['imported']);
        $this->assertSame(1, $report['skipped']);
        $this->assertNotEmpty($report['errors']);

        @unlink($spreadsheetPath);
    }

    public function test_admin_can_download_import_template(): void
    {
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $response = $this->actingAs($admin)->get(route('admin.images.import.template'));

        $response->assertOk();
        $response->assertHeader('content-type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
    }

    protected function makeImportSpreadsheet(array $rows): string
    {
        $spreadsheet = new Spreadsheet();
        $spreadsheet->getActiveSheet()->fromArray($rows);

        $path = tempnam(sys_get_temp_dir(), 'gaf-import-test-');
        $writer = new Xlsx($spreadsheet);
        $writer->save($path);

        return $path;
    }
}
