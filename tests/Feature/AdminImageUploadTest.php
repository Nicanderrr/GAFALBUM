<?php

namespace Tests\Feature;

use App\Models\Image;
use App\Models\ImageMedia;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminImageUploadTest extends TestCase
{
    use RefreshDatabase;

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
}
