<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\ImageMedia;
use App\Models\Transaction;
use App\Models\TransactionItem;
use App\Services\PaystackService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PaymentController extends Controller
{
    public function checkoutMedia(PaystackService $paystack, ImageMedia $media)
    {
        $media->load('image');

        $existingPurchase = TransactionItem::where('image_media_id', $media->id)
            ->whereHas('transaction', fn ($query) => $query
                ->where('user_id', auth()->id())
                ->where('status', 'success'))
            ->first();

        if ($existingPurchase) {
            return redirect()->route('purchases.download', $existingPurchase);
        }

        $amount = (float) $media->image->price;
        $reference = 'GAF-'.Str::upper(Str::random(18));

        $transaction = DB::transaction(function () use ($media, $amount, $reference) {
            $transaction = Transaction::create([
                'user_id' => auth()->id(),
                'image_id' => $media->image_id,
                'reference' => $reference,
                'amount' => $amount,
                'status' => 'pending',
            ]);

            $transaction->items()->create([
                'image_id' => $media->image_id,
                'image_media_id' => $media->id,
                'amount' => $amount,
            ]);

            return $transaction;
        });

        $response = $paystack->initializePayment([
            'email' => auth()->user()->email,
            'amount' => (int) round($amount * 100),
            'currency' => config('services.paystack.currency', 'GHS'),
            'reference' => $reference,
            'callback_url' => route('payments.paystack.callback'),
            'metadata' => [
                'transaction_id' => $transaction->id,
                'user_id' => auth()->id(),
                'image_media_id' => $media->id,
                'quick_download' => true,
            ],
        ]);

        $authorizationUrl = data_get($response, 'data.authorization_url');

        if (! $authorizationUrl) {
            $transaction->update(['status' => 'failed']);

            return back()->withErrors(['paystack' => 'Unable to start Paystack checkout. Add your Paystack keys and try again.']);
        }

        return redirect()->away($authorizationUrl);
    }

    public function checkout(PaystackService $paystack)
    {
        $cartItems = CartItem::with(['image', 'media'])
            ->where('user_id', auth()->id())
            ->oldest()
            ->get();

        if ($cartItems->isEmpty()) {
            return redirect()->route('cart.index')->withErrors(['cart' => 'Your cart is empty.']);
        }

        $amount = $cartItems->sum(fn ($item) => (float) $item->amount);
        $reference = 'GAF-'.Str::upper(Str::random(18));

        $transaction = DB::transaction(function () use ($cartItems, $amount, $reference) {
            $transaction = Transaction::create([
                'user_id' => auth()->id(),
                'image_id' => $cartItems->first()->image_id,
                'reference' => $reference,
                'amount' => $amount,
                'status' => 'pending',
            ]);

            foreach ($cartItems as $item) {
                $transaction->items()->create([
                    'image_id' => $item->image_id,
                    'image_media_id' => $item->image_media_id,
                    'amount' => $item->amount,
                ]);
            }

            return $transaction;
        });

        $response = $paystack->initializePayment([
            'email' => auth()->user()->email,
            'amount' => (int) round($amount * 100),
            'currency' => config('services.paystack.currency', 'GHS'),
            'reference' => $reference,
            'callback_url' => route('payments.paystack.callback'),
            'metadata' => [
                'transaction_id' => $transaction->id,
                'user_id' => auth()->id(),
                'cart_items' => $cartItems->pluck('id')->values()->all(),
            ],
        ]);

        $authorizationUrl = data_get($response, 'data.authorization_url');

        if (! $authorizationUrl) {
            $transaction->update(['status' => 'failed']);

            return redirect()
                ->route('cart.index')
                ->withErrors(['paystack' => 'Unable to start Paystack checkout. Add your Paystack keys and try again.']);
        }

        return redirect()->away($authorizationUrl);
    }

    public function callback(PaystackService $paystack)
    {
        $reference = request('reference');

        if (! $reference) {
            return redirect()->route('cart.index')->withErrors(['paystack' => 'Missing Paystack reference.']);
        }

        $transaction = Transaction::with('items')
            ->where('reference', $reference)
            ->where('user_id', auth()->id())
            ->firstOrFail();

        if ($transaction->status === 'success') {
            return redirect()->route('purchases.index')->with('success', 'Payment already confirmed.');
        }

        $verification = $paystack->verifyPayment($reference);
        $status = data_get($verification, 'data.status');
        $paidAmount = (int) data_get($verification, 'data.amount', 0);
        $expectedAmount = (int) round(((float) $transaction->amount) * 100);

        if ($status === 'success' && $paidAmount >= $expectedAmount) {
            DB::transaction(function () use ($transaction) {
                $transaction->update(['status' => 'success']);

                CartItem::where('user_id', $transaction->user_id)
                    ->whereIn('image_media_id', $transaction->items->pluck('image_media_id'))
                    ->delete();
            });

            return redirect()->route('purchases.index')->with('success', 'Payment confirmed. Downloads are unlocked.');
        }

        $transaction->update(['status' => $status ?: 'failed']);

        return redirect()->route('cart.index')->withErrors(['paystack' => 'Payment was not successful.']);
    }

    public function download(TransactionItem $transactionItem)
    {
        $transactionItem->load(['transaction', 'media']);

        abort_unless($transactionItem->transaction->user_id === auth()->id(), 403);
        abort_unless($transactionItem->transaction->status === 'success', 403);

        $path = $transactionItem->media->file_path;

        abort_unless(Storage::disk('public')->exists($path), 404);

        return Storage::disk('public')->download($path);
    }
}
