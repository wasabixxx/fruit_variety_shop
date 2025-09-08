<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// ============================================================================
// PUBLIC ROUTES - Không cần đăng nhập
// ============================================================================

// Trang chủ
Route::get('/', function () {
    $products = Product::with('category')->paginate(9);
    $totalProducts = Product::count();
    $totalCategories = Category::count();
    return view('welcome', compact('products', 'totalProducts', 'totalCategories'));
})->name('home');

// Sản phẩm công khai
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('products.show');

// Danh mục công khai
Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
Route::get('/categories/{category}', [CategoryController::class, 'show'])->name('categories.show');

// ============================================================================
// AUTHENTICATION ROUTES - Xác thực người dùng
// ============================================================================

// Login & Register
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Email verification
Route::get('/email/verify/{token}', [AuthController::class, 'verifyEmail'])->name('email.verify');
Route::get('/email/verify', [AuthController::class, 'showVerificationNotice'])->name('email.verification.notice')->middleware('auth');
Route::post('/email/resend', [AuthController::class, 'resendVerification'])->name('email.resend')->middleware('auth');

// ============================================================================
// CART ROUTES - Giỏ hàng
// ============================================================================

Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add/{product}', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update/{product}', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove/{product}', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// ============================================================================
// ORDER ROUTES - Đơn hàng
// ============================================================================

// Tạo đơn hàng (không cần đăng nhập)
Route::get('/order', [OrderController::class, 'create'])->name('orders.create');
Route::post('/order', [OrderController::class, 'store'])->name('orders.store');

// Thanh toán
Route::get('/order/payment', [OrderController::class, 'payment'])->name('orders.payment');
Route::get('/order/payment-fallback', function() {
    $orderInfo = session('order_info');
    return view('orders.payment-mock', compact('orderInfo'));
})->name('orders.payment-fallback');

// MoMo payment
Route::get('/order/momo-return', [OrderController::class, 'momoReturn'])->name('orders.momo-return');
Route::post('/order/momo-notify', [OrderController::class, 'momoNotify'])->name('orders.momo-notify');
Route::get('/order/success', [OrderController::class, 'success'])->name('orders.success');

// Lịch sử đơn hàng của user (cần đăng nhập)
Route::middleware('auth')->group(function() {
    Route::get('/my-orders', [OrderController::class, 'myOrders'])->name('orders.my-orders');
    Route::get('/my-orders/{order}', [OrderController::class, 'myOrderDetail'])->name('orders.my-order-detail');
});

// ============================================================================
// ADMIN ROUTES - Quản trị viên
// ============================================================================

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function() {
    
    // Dashboard
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard.index');
    
    // ========================================================================
    // CATEGORY MANAGEMENT - Quản lý danh mục
    // ========================================================================
    Route::prefix('categories')->name('categories.')->group(function() {
        Route::get('/', [AdminCategoryController::class, 'index'])->name('index');
        Route::get('/create', [AdminCategoryController::class, 'create'])->name('create');
        Route::post('/', [AdminCategoryController::class, 'store'])->name('store');
        Route::get('/{category}', [AdminCategoryController::class, 'show'])->name('show');
        Route::get('/{category}/edit', [AdminCategoryController::class, 'edit'])->name('edit');
        Route::put('/{category}', [AdminCategoryController::class, 'update'])->name('update');
        Route::patch('/{category}', [AdminCategoryController::class, 'update'])->name('update');
        Route::delete('/{category}', [AdminCategoryController::class, 'destroy'])->name('destroy');
    });
    
    // ========================================================================
    // PRODUCT MANAGEMENT - Quản lý sản phẩm
    // ========================================================================
    Route::prefix('products')->name('products.')->group(function() {
        Route::get('/', [AdminProductController::class, 'index'])->name('index');
        Route::get('/create', [AdminProductController::class, 'create'])->name('create');
        Route::post('/', [AdminProductController::class, 'store'])->name('store');
        Route::get('/{product}', [AdminProductController::class, 'show'])->name('show');
        Route::get('/{product}/edit', [AdminProductController::class, 'edit'])->name('edit');
        Route::put('/{product}', [AdminProductController::class, 'update'])->name('update');
        Route::patch('/{product}', [AdminProductController::class, 'update'])->name('update');
        Route::delete('/{product}', [AdminProductController::class, 'destroy'])->name('destroy');
    });
    
    // ========================================================================
    // ORDER MANAGEMENT - Quản lý đơn hàng
    // ========================================================================
    Route::prefix('orders')->name('orders.')->group(function() {
        Route::get('/', [AdminController::class, 'orders'])->name('index');
        Route::get('/{order}', [AdminController::class, 'orderDetail'])->name('show');
        Route::patch('/{order}/status', [AdminController::class, 'updateOrderStatus'])->name('update-status');
        Route::delete('/{order}', [AdminController::class, 'deleteOrder'])->name('destroy');
        Route::delete('/{order}/delete', [AdminController::class, 'deleteOrder'])->name('delete');
    });
    
    // ========================================================================
    // USER MANAGEMENT - Quản lý người dùng
    // ========================================================================
    Route::prefix('users')->name('users.')->group(function() {
        Route::get('/', [AdminController::class, 'users'])->name('index');
        Route::get('/{user}', [AdminController::class, 'userDetail'])->name('show');
        Route::get('/{user}/orders', [AdminController::class, 'userOrders'])->name('orders');
    });
    
});