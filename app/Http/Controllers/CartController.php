<?php

namespace App\Http\Controllers;

use App\Models\CartItem;
use App\Models\ImageMedia;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public function index()
    {
        $cartItems = CartItem::with(['image.category', 'media'])
            ->where('user_id', auth()->id())
            ->latest()
            ->get();

        return view('user.cart.index', compact('cartItems'));
    }

    public function store(Request $request, ImageMedia $media)
    {
        $media->load('image');

        $cartItem = CartItem::firstOrCreate(
            [
                'user_id' => auth()->id(),
                'image_media_id' => $media->id,
            ],
            [
                'image_id' => $media->image_id,
                'amount' => $media->image->price,
            ]
        );

        if ($request->expectsJson()) {
            return response()->json([
                'ok' => true,
                'cart_count' => CartItem::where('user_id', auth()->id())->count(),
                'already_exists' => ! $cartItem->wasRecentlyCreated,
            ]);
        }

        if ($request->input('redirect_to') === 'cart') {
            return redirect()->route('cart.index')->with('success', 'Photo added to cart. Complete payment to unlock the download.');
        }

        return back()->with('success', 'Photo added to cart.');
    }

    public function destroy(CartItem $cartItem)
    {
        abort_unless($cartItem->user_id === auth()->id(), 403);

        $cartItem->delete();

        return back()->with('success', 'Photo removed from cart.');
    }
}
