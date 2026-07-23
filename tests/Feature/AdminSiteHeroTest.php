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
            ])
            ->assertRedirect(route('admin.site-heroes.index'));

        $this->assertDatabaseHas('site_heroes', ['key' => 'dashboard_background']);
        $this->assertDatabaseHas('site_heroes', ['key' => 'dashboard_foreground']);

        Storage::disk('public')->assertExists(SiteHero::where('key', 'dashboard_background')->firstOrFail()->image_path);
        Storage::disk('public')->assertExists(SiteHero::where('key', 'dashboard_foreground')->firstOrFail()->image_path);
    }
}
