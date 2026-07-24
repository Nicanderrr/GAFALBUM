<?php

namespace Tests\Feature;

use App\Models\Image;
use App\Models\ImageMedia;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminPaymentsIndexTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_and_filter_payments(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $buyer = User::factory()->create([
            'name' => 'Jane Doe',
            'service_number' => 'GAF-1001',
        ]);

        $image = Image::create([
            'title' => 'Passing Out Parade',
            'price' => 50,
            'status' => 'published',
            'file_path' => 'images/parade.jpg',
            'thumbnail_path' => 'images/parade.jpg',
            'admin_id' => $admin->id,
            'published_at' => now(),
        ]);

        $media = ImageMedia::create([
            'image_id' => $image->id,
            'file_path' => 'images/parade.jpg',
            'media_type' => 'image',
            'sort_order' => 0,
        ]);

        $paid = Transaction::create([
            'user_id' => $buyer->id,
            'image_id' => $image->id,
            'reference' => 'GAF-PAID-001',
            'amount' => 50,
            'status' => 'success',
        ]);

        $paid->items()->create([
            'image_id' => $image->id,
            'image_media_id' => $media->id,
            'amount' => 50,
        ]);

        Transaction::create([
            'user_id' => $buyer->id,
            'image_id' => $image->id,
            'reference' => 'GAF-PENDING-002',
            'amount' => 50,
            'status' => 'pending',
        ]);

        $this->actingAs($admin)
            ->get(route('admin.payments.index'))
            ->assertOk()
            ->assertSee('Payments and Downloads')
            ->assertSee('GAF-PAID-001')
            ->assertSee('GAF-PENDING-002')
            ->assertSee('Jane Doe')
            ->assertSee('GAF-1001');

        $this->actingAs($admin)
            ->get(route('admin.payments.index', ['status' => 'success', 'search' => 'PAID-001']))
            ->assertOk()
            ->assertSee('GAF-PAID-001')
            ->assertDontSee('GAF-PENDING-002');
    }
}
