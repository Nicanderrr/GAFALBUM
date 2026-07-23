<?php

namespace Tests\Feature;

use App\Models\CartItem;
use App\Models\Image;
use App\Models\ImageMedia;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CartPaymentTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_add_photo_to_cart(): void
    {
        [$user, $media] = $this->makeUserAndMedia();

        $this->actingAs($user)
            ->post(route('cart.items.store', $media))
            ->assertSessionHas('success');

        $this->assertDatabaseHas('cart_items', [
            'user_id' => $user->id,
            'image_media_id' => $media->id,
            'amount' => 15,
        ]);
    }

    public function test_ajax_add_photo_to_cart_returns_current_cart_count(): void
    {
        [$user, $media] = $this->makeUserAndMedia();

        $this->actingAs($user)
            ->postJson(route('cart.items.store', $media))
            ->assertOk()
            ->assertJson([
                'ok' => true,
                'cart_count' => 1,
                'already_exists' => false,
            ]);

        $this->actingAs($user)
            ->postJson(route('cart.items.store', $media))
            ->assertOk()
            ->assertJson([
                'ok' => true,
                'cart_count' => 1,
                'already_exists' => true,
            ]);
    }

    public function test_checkout_initializes_paystack_and_creates_pending_transaction(): void
    {
        config(['services.paystack.secret_key' => 'sk_test_xxx']);

        Http::fake([
            'https://api.paystack.co/transaction/initialize' => Http::response([
                'status' => true,
                'data' => [
                    'authorization_url' => 'https://checkout.paystack.com/test',
                    'reference' => 'ref',
                ],
            ]),
        ]);

        [$user, $media] = $this->makeUserAndMedia();

        CartItem::create([
            'user_id' => $user->id,
            'image_id' => $media->image_id,
            'image_media_id' => $media->id,
            'amount' => 15,
        ]);

        $this->actingAs($user)
            ->post(route('cart.checkout'))
            ->assertRedirect('https://checkout.paystack.com/test');

        $transaction = Transaction::with('items')->firstOrFail();

        $this->assertSame('pending', $transaction->status);
        $this->assertCount(1, $transaction->items);
        $this->assertSame($media->id, $transaction->items->first()->image_media_id);
    }

    public function test_quick_media_payment_initializes_paystack_without_cart_checkout(): void
    {
        config(['services.paystack.secret_key' => 'sk_test_xxx']);

        Http::fake([
            'https://api.paystack.co/transaction/initialize' => Http::response([
                'status' => true,
                'data' => [
                    'authorization_url' => 'https://checkout.paystack.com/quick-test',
                    'reference' => 'ref',
                ],
            ]),
        ]);

        [$user, $media] = $this->makeUserAndMedia();

        $this->actingAs($user)
            ->post(route('payments.media.checkout', $media))
            ->assertRedirect('https://checkout.paystack.com/quick-test');

        $transaction = Transaction::with('items')->firstOrFail();

        $this->assertSame('pending', $transaction->status);
        $this->assertSame(15.0, (float) $transaction->amount);
        $this->assertCount(1, $transaction->items);
        $this->assertSame($media->id, $transaction->items->first()->image_media_id);
        $this->assertDatabaseCount('cart_items', 0);
    }

    public function test_quick_media_payment_redirects_to_download_when_already_purchased(): void
    {
        [$user, $media] = $this->makeUserAndMedia();

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'image_id' => $media->image_id,
            'reference' => 'GAF-PAID',
            'amount' => 15,
            'status' => 'success',
        ]);

        $item = $transaction->items()->create([
            'image_id' => $media->image_id,
            'image_media_id' => $media->id,
            'amount' => 15,
        ]);

        $this->actingAs($user)
            ->post(route('payments.media.checkout', $media))
            ->assertRedirect(route('purchases.download', $item));
    }


    public function test_successful_paystack_callback_marks_transaction_success_and_clears_cart(): void
    {
        config(['services.paystack.secret_key' => 'sk_test_xxx']);

        Http::fake([
            'https://api.paystack.co/transaction/verify/GAF-TEST' => Http::response([
                'status' => true,
                'data' => [
                    'status' => 'success',
                    'amount' => 1500,
                ],
            ]),
        ]);

        [$user, $media] = $this->makeUserAndMedia();

        $cartItem = CartItem::create([
            'user_id' => $user->id,
            'image_id' => $media->image_id,
            'image_media_id' => $media->id,
            'amount' => 15,
        ]);

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'image_id' => $media->image_id,
            'reference' => 'GAF-TEST',
            'amount' => 15,
            'status' => 'pending',
        ]);

        $transaction->items()->create([
            'image_id' => $media->image_id,
            'image_media_id' => $media->id,
            'amount' => 15,
        ]);

        $this->actingAs($user)
            ->get(route('payments.paystack.callback', ['reference' => 'GAF-TEST']))
            ->assertRedirect(route('purchases.index'));

        $this->assertSame('success', $transaction->fresh()->status);
        $this->assertDatabaseMissing('cart_items', ['id' => $cartItem->id]);
    }

    public function test_purchases_page_only_lists_successful_transactions(): void
    {
        [$user, $media] = $this->makeUserAndMedia();

        $successful = Transaction::create([
            'user_id' => $user->id,
            'image_id' => $media->image_id,
            'reference' => 'GAF-SUCCESS',
            'amount' => 15,
            'status' => 'success',
        ]);

        $successful->items()->create([
            'image_id' => $media->image_id,
            'image_media_id' => $media->id,
            'amount' => 15,
        ]);

        Transaction::create([
            'user_id' => $user->id,
            'image_id' => $media->image_id,
            'reference' => 'GAF-PENDING',
            'amount' => 15,
            'status' => 'pending',
        ]);

        $this->actingAs($user)
            ->get(route('purchases.index'))
            ->assertOk()
            ->assertSee('GAF-SUCCESS')
            ->assertDontSee('GAF-PENDING');
    }

    public function test_gallery_event_shows_download_link_only_for_purchased_media(): void
    {
        [$user, $media] = $this->makeUserAndMedia();

        $transaction = Transaction::create([
            'user_id' => $user->id,
            'image_id' => $media->image_id,
            'reference' => 'GAF-DOWNLOAD',
            'amount' => 15,
            'status' => 'success',
        ]);

        $item = $transaction->items()->create([
            'image_id' => $media->image_id,
            'image_media_id' => $media->id,
            'amount' => 15,
        ]);

        $this->actingAs($user)
            ->get(route('gallery.show', $media->image_id))
            ->assertOk()
            ->assertSee(route('purchases.download', $item), false)
            ->assertDontSee('data-title="Photo Event - Photo 1"', false);
    }

    public function test_gallery_event_opens_payment_modal_for_unpurchased_media(): void
    {
        [$user, $media] = $this->makeUserAndMedia();

        $this->actingAs($user)
            ->get(route('gallery.show', $media->image_id))
            ->assertOk()
            ->assertSee('gaf-open-download-modal')
            ->assertSee('Pay')
            ->assertSee(route('payments.media.checkout', $media), false);
    }

    private function makeUserAndMedia(): array
    {
        $user = User::factory()->create();
        $admin = User::factory()->create(['is_admin' => true]);

        $image = Image::create([
            'title' => 'Photo Event',
            'price' => 15,
            'file_path' => 'images/photo.jpg',
            'thumbnail_path' => 'images/photo.jpg',
            'admin_id' => $admin->id,
        ]);

        $media = ImageMedia::create([
            'image_id' => $image->id,
            'file_path' => 'images/photo.jpg',
            'media_type' => 'image',
            'sort_order' => 0,
        ]);

        return [$user, $media];
    }
}
