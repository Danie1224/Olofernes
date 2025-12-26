<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\AdminAuthController;
use App\Http\Controllers\AdminVoucherController;
use App\Http\Controllers\UserVoucherController;
use App\Http\Controllers\UserReturnRequestController;   
use App\Http\Controllers\AdminReturnRequestController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

Route::middleware('web')->group(function () {
// Public routes
Route::get('/', function () {
    $featuredProducts = \App\Models\Product::where('stock_quantity', '>', 0)
        ->inRandomOrder()
        ->limit(6)
        ->get();
    
    return view('home', compact('featuredProducts'));
})->name('home');

Route::middleware('auth')->group(function () {
    Route::get('/products', [ProductController::class, 'index'])->name('products.index');
    Route::get('/products/{id}', [ProductController::class, 'show'])->name('products.show');
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::get('/checkout', [App\Http\Controllers\CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout/process', [App\Http\Controllers\CheckoutController::class, 'processCheckout'])->name('checkout.process');
    
    // Payment method dedicated pages
    Route::get('/checkout/gcash-details', [App\Http\Controllers\CheckoutController::class, 'showGCashDetails'])->name('checkout.gcash-details');
    Route::get('/checkout/credit-card-details', [App\Http\Controllers\CheckoutController::class, 'showCreditCardDetails'])->name('checkout.credit-card-details');
    Route::get('/checkout/paypal-details', [App\Http\Controllers\CheckoutController::class, 'showPayPalDetails'])->name('checkout.paypal-details');
    
    // User order items dashboard routes
    Route::get('/orders', [App\Http\Controllers\UserOrderItemController::class, 'index'])->name('user.orders.index');
    Route::get('/orders/{orderId}', [App\Http\Controllers\UserOrderItemController::class, 'show'])->name('user.orders.show');
    Route::get('/orders-stats', [App\Http\Controllers\UserOrderItemController::class, 'stats'])->name('user.orders.stats');

    // User return requests routes
    Route::get('/returns', [App\Http\Controllers\UserReturnRequestController::class, 'index'])->name('returns.index');
    Route::get('/returns/create', [App\Http\Controllers\UserReturnRequestController::class, 'create'])->name('returns.create');
    Route::post('/returns', [App\Http\Controllers\UserReturnRequestController::class, 'store'])->name('returns.store');
    Route::get('/returns/{requestId}', [App\Http\Controllers\UserReturnRequestController::class, 'show'])->name('returns.show');
    Route::delete('/returns/{requestId}', [App\Http\Controllers\UserReturnRequestController::class, 'cancel'])->name('returns.cancel');
    Route::get('/returns-stats', [App\Http\Controllers\UserReturnRequestController::class, 'stats'])->name('returns.stats');
});

// Auth routes
Route::get('/login', [App\Http\Controllers\AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [App\Http\Controllers\AuthController::class, 'login']);
Route::get('/register', [App\Http\Controllers\AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [App\Http\Controllers\AuthController::class, 'register']);
Route::post('/logout', [App\Http\Controllers\AuthController::class, 'logout'])->name('logout');

// Admin web-auth routes (separate guard)
Route::middleware('guest:admin')->group(function () {
    Route::get('/admin/login', [AdminAuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('/admin/login', [AdminAuthController::class, 'login'])->name('admin.login.submit');
    Route::get('/admin/register', [AdminAuthController::class, 'showRegisterForm'])->name('admin.register');
    Route::post('/admin/register', [AdminAuthController::class, 'register'])->name('admin.register.submit');
});

// Explicit logout for web users (logout web guard only)
Route::post('/logout-web', function (Request $request) {
    Auth::guard('web')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/');
})->name('logout.web');

// Explicit logout for admin (logout admin guard only)
Route::post('/admin/logout', function (Request $request) {
    Auth::guard('admin')->logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/admin/login');
})->name('admin.logout')->middleware('auth:admin');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

// Admin routes
// Authenticated helpers
Route::middleware('auth')->get('/me/vouchers', function () {
    $userId = auth()->id();
    $userVouchers = \App\Models\UserVoucher::where('user_id', $userId)
        ->where('status', 'active')
        ->join('vouchers', 'vouchers.voucher_id', '=', 'user_vouchers.voucher_id')
        ->where('vouchers.status', 'active')
        ->get(['vouchers.*', 'user_vouchers.user_voucher_id']);
    return response()->json($userVouchers);
})->name('me.vouchers');

// User voucher dashboard and claim
Route::middleware('auth')->group(function () {
    Route::get('/vouchers', [UserVoucherController::class, 'index'])->name('vouchers.index');
    Route::post('/vouchers/claim', [UserVoucherController::class, 'claim'])->name('vouchers.claim');
    Route::post('/vouchers/validate', [UserVoucherController::class, 'validate'])->name('vouchers.validate');
    Route::delete('/vouchers/{userVoucherId}', [UserVoucherController::class, 'revoke'])->name('vouchers.revoke');
    Route::get('/me/vouchers-available', [UserVoucherController::class, 'getAvailable'])->name('vouchers.available');
});
Route::prefix('admin')->middleware('auth:admin')->group(function () {
    Route::get('/', [AdminController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/products', [AdminController::class, 'productsIndex'])->name('admin.products.index');
    Route::get('/products/create', [AdminController::class, 'productsCreate'])->name('admin.products.create');
    Route::post('/products', [AdminController::class, 'productsStore'])->name('admin.products.store');
    Route::get('/products/{product}/edit', [AdminController::class, 'productsEdit'])->name('admin.products.edit');
    Route::put('/products/{product}', [AdminController::class, 'productsUpdate'])->name('admin.products.update');
    Route::delete('/products/{product}', [AdminController::class, 'productsDestroy'])->name('admin.products.destroy');
    Route::get('/customers', [AdminController::class, 'customersIndex'])->name('admin.customers.index');
    Route::get('/orders', [AdminController::class, 'ordersIndex'])->name('admin.orders.index');
    Route::get('/orders/{order}', [AdminController::class, 'ordersShow'])->name('admin.orders.show');
    Route::post('/orders/{orderId}/complete', [AdminController::class, 'completeOrder'])->name('admin.orders.complete');
    
    // Return Request Management Routes
    Route::get('/returns', [AdminReturnRequestController::class, 'index'])->name('admin.returns.index');
    Route::get('/returns/{requestId}', [AdminReturnRequestController::class, 'show'])->name('admin.returns.show');
    Route::post('/returns/{requestId}/approve', [AdminReturnRequestController::class, 'approve'])->name('admin.returns.approve');
    Route::post('/returns/{requestId}/reject', [AdminReturnRequestController::class, 'reject'])->name('admin.returns.reject');
    Route::post('/returns/{requestId}/refund', [AdminReturnRequestController::class, 'refund'])->name('admin.returns.refund');
    Route::get('/returns-stats', [AdminReturnRequestController::class, 'stats'])->name('admin.returns.stats');
    
    // Voucher Management Routes
    Route::get('/vouchers', [AdminVoucherController::class, 'index'])->name('admin.vouchers.index');
    Route::get('/vouchers/create', [AdminVoucherController::class, 'create'])->name('admin.vouchers.create');
    Route::post('/vouchers', [AdminVoucherController::class, 'store'])->name('admin.vouchers.store');
    Route::get('/vouchers/{voucher}', [AdminVoucherController::class, 'show'])->name('admin.vouchers.show');
    Route::get('/vouchers/{voucher}/edit', [AdminVoucherController::class, 'edit'])->name('admin.vouchers.edit');
    Route::put('/vouchers/{voucher}', [AdminVoucherController::class, 'update'])->name('admin.vouchers.update');
    Route::delete('/vouchers/{voucher}', [AdminVoucherController::class, 'destroy'])->name('admin.vouchers.destroy');
    Route::post('/vouchers/{voucher}/redistribute', [AdminVoucherController::class, 'redistribute'])->name('admin.vouchers.redistribute');
    Route::get('/vouchers/{voucher}/stats', [AdminVoucherController::class, 'stats'])->name('admin.vouchers.stats');
});

});
