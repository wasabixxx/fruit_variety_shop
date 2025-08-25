<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Route;

// Public routes
Route::get('/', function () {
    $products = Product::with('category')->paginate(9);
    $totalProducts = Product::count();
    $totalCategories = Category::count();
    return view('welcome', compact('products', 'totalProducts', 'totalCategories'));
});

// Public product routes (không cần đăng nhập)
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

// Auth routes
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Email verification routes
Route::get('/email/verify/{token}', [AuthController::class, 'verifyEmail'])->name('email.verify');
Route::get('/email/verify', [AuthController::class, 'showVerificationNotice'])->name('email.verification.notice')->middleware('auth');
Route::post('/email/resend', [AuthController::class, 'resendVerification'])->name('email.resend')->middleware('auth');

// Admin routes
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::resource('categories', AdminCategoryController::class);
    Route::resource('products', AdminProductController::class);
});

// Cart routes
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{product}', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// Order routes
Route::get('/order', [OrderController::class, 'create'])->name('orders.create');
Route::post('/order', [OrderController::class, 'store'])->name('orders.store');
Route::get('/order/payment', [OrderController::class, 'payment'])->name('orders.payment');
Route::post('/order/confirm-payment', [OrderController::class, 'confirmPayment'])->name('orders.confirm-payment');
Route::get('/order/success', [OrderController::class, 'success'])->name('orders.success');