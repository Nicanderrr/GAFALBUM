<?php

namespace Tests\Feature;

use App\Models\SiteHero;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class AdminSiteHeroTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_upload_dashboard_hero_images(): void
    {
        Storage::fake('public');

        $admin = User::factory()->create(['is_admin' => true]);

        $this->actingAs($admin)
            ->put(route('admin.site-heroes.update'), [
                'dashboard_background' => UploadedFile::fake()->image('dashboard-bg.jpg', 1600, 900),
                'dashboard_foreground' => UploadedFile::fake()->image('dashboard-fg.jpg', 900, 900),
                'dashboard_process_1' => UploadedFile::fake()->image('process-1.jpg', 1200, 1500),
                'dashboard_process_2' => UploadedFile::fake()->image('process-2.jpg', 1200, 1500),
                'dashboard_process_3' => UploadedFile::fake()->image('process-3.jpg', 1200, 1500),
            ])
            ->assertRedirect(route('admin.site-heroes.index'));

        $this->assertDatabaseHas('site_heroes', ['key' => 'dashboard_background']);
        $this->assertDatabaseHas('site_heroes', ['key' => 'dashboard_foreground']);
        $this->assertDatabaseHas('site_heroes', ['key' => 'dashboard_process_1']);
        $this->assertDatabaseHas('site_heroes', ['key' => 'dashboard_process_2']);
        $this->assertDatabaseHas('site_heroes', ['key' => 'dashboard_process_3']);

        Storage::disk('public')->assertExists(SiteHero::where('key', 'dashboard_background')->firstOrFail()->image_path);
        Storage::disk('public')->assertExists(SiteHero::where('key', 'dashboard_foreground')->firstOrFail()->image_path);
        Storage::disk('public')->assertExists(SiteHero::where('key', 'dashboard_process_1')->firstOrFail()->image_path);
        Storage::disk('public')->assertExists(SiteHero::where('key', 'dashboard_process_2')->firstOrFail()->image_path);
        Storage::disk('public')->assertExists(SiteHero::where('key', 'dashboard_process_3')->firstOrFail()->image_path);
    }
}
