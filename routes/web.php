<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\PaymentController;
use Illuminate\Support\Facades\Route;

Route::redirect('/', '/dashboard');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/gallery', function () {
        $search = trim((string) request('search'));
        $imagesQuery = \App\Models\Image::published()->with(['category', 'coverMedia', 'media'])->withCount('media');

        if ($search !== '') {
            $imagesQuery->where(function ($query) use ($search) {
                $query
                    ->where('title', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhereHas('category', fn ($categoryQuery) => $categoryQuery->where('name', 'like', "%{$search}%"));
            })->latest();
        } else {
            $imagesQuery->inRandomOrder();
        }

        $images = $imagesQuery->paginate(12)->withQueryString();
        $purchasedItems = \App\Models\TransactionItem::whereHas('transaction', fn ($query) => $query
            ->where('user_id', auth()->id())
            ->where('status', 'success'))
            ->pluck('id', 'image_media_id');

        return view('user.gallery.index', compact('images', 'purchasedItems', 'search'));
    })->name('gallery.index');

    Route::get('/gallery/{image}', function (\App\Models\Image $image) {
        abort_unless($image->status === 'published', 404);
        $image->load(['category', 'media']);
        $purchasedItems = \App\Models\TransactionItem::whereHas('transaction', fn ($query) => $query
            ->where('user_id', auth()->id())
            ->where('status', 'success'))
            ->pluck('id', 'image_media_id');

        return view('user.gallery.show', compact('image', 'purchasedItems'));
    })->name('gallery.show');

    Route::get('/gallery/{image}/experience', function (\App\Models\Image $image) {
        abort_unless($image->status === 'published', 404);
        $image->load(['category', 'media']);
        return view('user.gallery.experience', compact('image'));
    })->name('gallery.experience');

    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/items/{media}', [CartController::class, 'store'])->name('cart.items.store');
    Route::delete('/cart/items/{cartItem}', [CartController::class, 'destroy'])->name('cart.items.destroy');
    Route::post('/cart/checkout', [PaymentController::class, 'checkout'])->name('cart.checkout');
    Route::post('/paystack/media/{media}', [PaymentController::class, 'checkoutMedia'])->name('payments.media.checkout');
    Route::get('/paystack/callback', [PaymentController::class, 'callback'])->name('payments.paystack.callback');
    Route::get('/purchases/download/{transactionItem}', [PaymentController::class, 'download'])->name('purchases.download');

    Route::get('/purchases', function () {
        $purchases = \App\Models\Transaction::with(['image', 'items.image', 'items.media'])
            ->where('user_id', auth()->id())
            ->where('status', 'success')
            ->latest()
            ->paginate(10);

        return view('user.purchases.index', compact('purchases'));
    })->name('purchases.index');
});

// Admin Routes
Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Categories
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    
    // Payments
    Route::get('/payments', [\App\Http\Controllers\Admin\PaymentController::class, 'index'])->name('payments.index');
    Route::post('/ai/chat', [\App\Http\Controllers\Admin\AdminAIController::class, 'chat'])->name('ai.chat');
    Route::post('/ai/analyze', [\App\Http\Controllers\Admin\AdminAIController::class, 'analyze'])->name('ai.analyze');

    // Images
    Route::resource('images', \App\Http\Controllers\Admin\ImageController::class);

    Route::get('/site-heroes', [\App\Http\Controllers\Admin\SiteHeroController::class, 'index'])->name('site-heroes.index');
    Route::put('/site-heroes', [\App\Http\Controllers\Admin\SiteHeroController::class, 'update'])->name('site-heroes.update');
    
    // Admins Management
    Route::resource('admins', \App\Http\Controllers\Admin\AdminUserController::class)->except(['show']);
    
    // Regular Users Management
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
});

require __DIR__.'/auth.php';
