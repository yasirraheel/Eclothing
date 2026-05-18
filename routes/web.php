<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function (\Illuminate\Http\Request $request) {
    $query = \App\Models\Product::where('stock', '>', 0);
    
    if ($request->filled('q')) {
        $query->where('name', 'like', '%' . $request->q . '%')
              ->orWhere('description', 'like', '%' . $request->q . '%');
    }
    
    $randomProduct = clone $query;
    $heroProduct = $randomProduct->inRandomOrder()->first();
    
    $products = $query->latest()->take(18)->get();
    $setting = \App\Models\Setting::first();
    
    $customerCareTitles = ['Help Center', 'How to Buy', 'Returns & Refunds', 'Contact Us'];
    $customerCarePages = \App\Models\Page::whereIn('title', $customerCareTitles)->get();
    $darazPages = \App\Models\Page::whereNotIn('title', $customerCareTitles)->get();
    
    return view('welcome', compact('products', 'setting', 'heroProduct', 'customerCarePages', 'darazPages'));
})->name('home');

Route::get('/page/{slug}', function ($slug) {
    $page = \App\Models\Page::where('slug', $slug)->firstOrFail();
    $setting = \App\Models\Setting::first();
    
    $customerCareTitles = ['Help Center', 'How to Buy', 'Returns & Refunds', 'Contact Us'];
    $customerCarePages = \App\Models\Page::whereIn('title', $customerCareTitles)->get();
    $darazPages = \App\Models\Page::whereNotIn('title', $customerCareTitles)->get();
    
    return view('page', compact('page', 'setting', 'customerCarePages', 'darazPages'));
})->name('frontend.page');

Route::get('/product/{id}', function ($id) {
    $product = \App\Models\Product::findOrFail($id);
    $setting = \App\Models\Setting::first();
    
    $customerCareTitles = ['Help Center', 'How to Buy', 'Returns & Refunds', 'Contact Us'];
    $customerCarePages = \App\Models\Page::whereIn('title', $customerCareTitles)->get();
    $darazPages = \App\Models\Page::whereNotIn('title', $customerCareTitles)->get();
    
    return view('product', compact('product', 'setting', 'customerCarePages', 'darazPages'));
})->name('frontend.product');

Route::get('/cart', [\App\Http\Controllers\CartController::class, 'index'])->name('cart.index');
Route::get('/cart/count', [\App\Http\Controllers\CartController::class, 'count'])->name('cart.count');
Route::post('/cart/add/{id}', [\App\Http\Controllers\CartController::class, 'add'])->name('cart.add');
Route::patch('/cart/update', [\App\Http\Controllers\CartController::class, 'update'])->name('cart.update');
Route::delete('/cart/remove', [\App\Http\Controllers\CartController::class, 'remove'])->name('cart.remove');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/checkout', [\App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [\App\Http\Controllers\CheckoutController::class, 'store'])->name('checkout.store');

    // User Orders
    Route::get('/my-orders', [\App\Http\Controllers\OrderController::class, 'index'])->name('orders.index');
    Route::get('/my-orders/{orderNumber}', [\App\Http\Controllers\OrderController::class, 'show'])->name('orders.show');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth', 'is_admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [\App\Http\Controllers\AdminController::class, 'dashboard'])->name('dashboard');
    Route::resource('users', \App\Http\Controllers\Admin\UserController::class);
    Route::resource('products', \App\Http\Controllers\ProductController::class);
    Route::resource('pages', \App\Http\Controllers\Admin\PageController::class);
    Route::get('settings', [\App\Http\Controllers\Admin\SettingController::class, 'edit'])->name('settings.edit');
    Route::post('settings', [\App\Http\Controllers\Admin\SettingController::class, 'update'])->name('settings.update');

    // Admin Orders
    Route::get('orders', [\App\Http\Controllers\Admin\OrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [\App\Http\Controllers\Admin\OrderController::class, 'show'])->name('orders.show');
    Route::patch('orders/{order}/status', [\App\Http\Controllers\Admin\OrderController::class, 'updateStatus'])->name('orders.updateStatus');
});
require __DIR__.'/auth.php';
