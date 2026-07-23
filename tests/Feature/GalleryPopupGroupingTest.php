<?php

namespace Tests\Feature;

use App\Models\Image;
use App\Models\ImageMedia;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GalleryPopupGroupingTest extends TestCase
{
    use RefreshDatabase;

    public function test_gallery_popup_groups_photos_by_event(): void
    {
        $user = User::factory()->create();
        $admin = User::factory()->create([
            'is_admin' => true,
        ]);

        $event = Image::create([
            'title' => 'Passing Out Parade',
            'price' => 20,
            'file_path' => 'images/parade-cover.jpg',
            'thumbnail_path' => 'images/parade-cover.jpg',
            'admin_id' => $admin->id,
        ]);

        foreach (['parade-cover.jpg', 'parade-two.jpg', 'parade-three.jpg'] as $index => $file) {
            ImageMedia::create([
                'image_id' => $event->id,
                'file_path' => 'images/'.$file,
                'media_type' => 'image',
                'sort_order' => $index,
            ]);
        }

        $response = $this->actingAs($user)->get(route('gallery.index'));

        $response->assertOk();
        $response->assertSee('data-group="'.$event->id.'"', false);
        $this->assertSame(3, substr_count($response->getContent(), 'data-group="'.$event->id.'"'));
        $response->assertSee('gaf-hidden-event-popup', false);
        $response->assertSee('data-cart-action', false);
        $response->assertSee('gaf-lightbox-cart-panel', false);
        $response->assertSee('gaf-lightbox-cart-form', false);
        $response->assertSee('Add to Cart', false);
    }
}
