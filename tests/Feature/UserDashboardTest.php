<?php

namespace Tests\Feature;

use App\Models\SiteHero;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class UserDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_dashboard_uses_configured_how_it_works_images(): void
    {
        Storage::fake('public');

        $user = User::factory()->create();

        SiteHero::create([
            'key' => 'dashboard_process_1',
            'image_path' => 'heroes/process-1.jpg',
        ]);

        SiteHero::create([
            'key' => 'dashboard_process_2',
            'image_path' => 'heroes/process-2.jpg',
        ]);

        SiteHero::create([
            'key' => 'dashboard_process_3',
            'image_path' => 'heroes/process-3.jpg',
        ]);

        $response = $this->actingAs($user)->get(route('dashboard'));

        $response->assertOk();
        $response->assertSee('Operations flow', false);
        $response->assertSee(Storage::disk('public')->url('heroes/process-1.jpg'), false);
        $response->assertSee(Storage::disk('public')->url('heroes/process-2.jpg'), false);
        $response->assertSee(Storage::disk('public')->url('heroes/process-3.jpg'), false);
    }
}
