<?php

use App\Http\Controllers\Auth\VendorRegistrationController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ShopController;
use App\Http\Controllers\Admin\VendorController as AdminVendorController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WishlistController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Route::get('/dashboard', function () {
//     return view('dashboard');
// })->middleware(['auth', 'verified'])->name('dashboard');

Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])->name('dashboard');
Route::patch('/dashboard/order/{order}/update', [DashboardController::class, 'updateOrderStatus'])
         ->name('dashboard.order.update');

Route::get('register/vendor', [VendorRegistrationController::class, 'create'])
     ->middleware('guest')
     ->name('vendor.register');
Route::post('register/vendor', [VendorRegistrationController::class, 'store'])
     ->middleware('guest')
     ->name('vendor.register.store');

require __DIR__.'/auth.php';

Route::middleware(['auth'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('categories', CategoryController::class);
    Route::resource('products', ProductController::class);
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::get('/vendors/pending', [AdminVendorController::class, 'index'])
             ->name('vendors.index');
        Route::patch('/vendors/{vendor}/approve', [AdminVendorController::class, 'approve'])
             ->name('vendors.approve');
        Route::patch('/vendors/{vendor}/reject', [AdminVendorController::class, 'reject'])
             ->name('vendors.reject');
    });
    Route::patch('/orders/{order}/complete', [OrderController::class, 'markAsCompleted'])
         ->name('orders.complete');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    Route::post('/wishlist/{product}', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::post('/products/{product}/review', [ReviewController::class, 'store'])->name('reviews.store');
});

Route::get('/shop', [ShopController::class, 'index'])->name('shop.index');
Route::get('/shop/{product}', [ShopController::class, 'show'])->name('shop.show');

// cart routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::delete('/cart/remove/{productId}', [CartController::class, 'remove'])->name('cart.remove');
Route::patch('/cart/update/{productId}', [CartController::class, 'update'])->name('cart.update');
// Ini dipicu oleh form 'Checkout'
Route::get('/checkout', [OrderController::class, 'create'])->name('checkout.index');
Route::post('/checkout', [OrderController::class, 'store'])->name('order.store');
// Ini halaman "Terima Kasih"
Route::get('/order/success', [OrderController::class, 'success'])->name('order.success');
Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
