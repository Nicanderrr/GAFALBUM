<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Image;
use App\Models\ImageMedia;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminDashboardAnalyticsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_shows_sales_totals_and_charts(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $user = User::factory()->create(['service_number' => 'USER-77']);
        $category = Category::create(['name' => 'Graduation', 'slug' => 'graduation']);

        $image = Image::create([
            'title' => 'Graduation Parade',
            'price' => 25,
            'file_path' => 'images/parade.jpg',
            'thumbnail_path' => 'images/parade.jpg',
            'category_id' => $category->id,
            'admin_id' => $admin->id,
        ]);

        $media = ImageMedia::create([
            'image_id' => $image->id,
            'file_path' => 'images/parade.jpg',
            'media_type' => 'image',
            'sort_order' => 0,
        ]);

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'image_id' => $image->id,
            'reference' => 'GAF-PAID',
            'amount' => 25,
            'status' => 'success',
        ]);

        $transaction->items()->create([
            'image_id' => $image->id,
            'image_media_id' => $media->id,
            'amount' => 25,
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'image_id' => $image->id,
            'reference' => 'GAF-PENDING',
            'amount' => 10,
            'status' => 'pending',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.dashboard'))
            ->assertOk()
            ->assertSee('Total Revenue')
            ->assertSee('GHS 25.00')
            ->assertSee('Files Sold')
            ->assertSee('Sales By Category')
            ->assertSee('Revenue Graph')
            ->assertSee('Graduation')
            ->assertSee('GAF-PAID')
            ->assertSee('GAF-PENDING');
    }
}
