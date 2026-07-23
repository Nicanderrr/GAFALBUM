<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Image;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminCategoryManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_categories_index_shows_event_counts(): void
    {
        $admin = User::factory()->create(['is_admin' => true]);
        $category = Category::create(['name' => 'Parades', 'slug' => 'parades']);

        Image::create([
            'title' => 'Passing Out Parade',
            'price' => 20,
            'file_path' => 'images/parade.jpg',
            'thumbnail_path' => 'images/parade.jpg',
            'category_id' => $category->id,
            'admin_id' => $admin->id,
        ]);

        $this->actingAs($admin)
            ->get(route('admin.categories.index'))
            ->assertOk()
            ->assertSee('Manage Categories')
            ->assertSee('Parades')
            ->assertSee('parades')
            ->assertSee('1 event');
    }
}
