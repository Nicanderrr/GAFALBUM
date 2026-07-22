<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect('/login');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/gallery', function () {
        $images = \App\Models\Image::with('category')->latest()->paginate(12);
        return view('user.gallery.index', compact('images'));
    })->name('gallery.index');

    Route::get('/gallery/{image}', function (\App\Models\Image $image) {
        return view('user.gallery.show', compact('image'));
    })->name('gallery.show');

    Route::get('/purchases', function () {
        $purchases = \App\Models\Transaction::with('image')->where('user_id', auth()->id())->latest()->paginate(10);
        return view('user.purchases.index', compact('purchases'));
    })->name('purchases.index');
});

// Admin Routes
Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    
    // Categories
    Route::resource('categories', \App\Http\Controllers\Admin\CategoryController::class);
    
    // Images
    Route::resource('images', \App\Http\Controllers\Admin\ImageController::class);
    
    // Admins Management
    Route::resource('admins', \App\Http\Controllers\Admin\AdminUserController::class)->except(['show']);
    
    // Regular Users Management
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
});

require __DIR__.'/auth.php';
