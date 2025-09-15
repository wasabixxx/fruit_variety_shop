<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminVoucherController;
use App\Http\Controllers\Admin\AdminPageController;
use App\Http\Controllers\Admin\ChartController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\RecommendationController;
use App\Http\Controllers\VoucherController;
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
    
    // Get recommendation service
    $recommendationService = app(\App\Services\RecommendationService::class);
    
    // Get recommendations based on user status
    if (auth()->check()) {
        $recommendedProducts = $recommendationService->getRecommendationsForUser(auth()->user(), 8);
    } else {
        $recommendedProducts = $recommendationService->getGuestRecommendations(8);
    }
    
    $popularProducts = $recommendationService->getPopularProducts(6);
    $trendingProducts = $recommendationService->getTrendingProducts(6);
    
    return view('welcome', compact('products', 'totalProducts', 'totalCategories', 'recommendedProducts', 'popularProducts', 'trendingProducts'));
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

// Password Reset Routes
Route::get('/password/reset', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/password/email', [App\Http\Controllers\Auth\ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
Route::get('/password/reset/{token}', [App\Http\Controllers\Auth\ResetPasswordController::class, 'showResetForm'])->name('password.reset');
Route::post('/password/reset', [App\Http\Controllers\Auth\ResetPasswordController::class, 'reset'])->name('password.update');

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
Route::post('/cart/remove/{product}', [CartController::class, 'remove'])->name('cart.remove.product');
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
Route::get('/test-momo', [OrderController::class, 'testMomo'])->name('test.momo'); // Test route

// Lịch sử đơn hàng của user (cần đăng nhập)
Route::middleware('auth')->group(function() {
    Route::get('/my-orders', [OrderController::class, 'myOrders'])->name('orders.my-orders');
    Route::get('/my-orders/{order}', [OrderController::class, 'myOrderDetail'])->name('orders.my-order-detail');
});

// ============================================================================
// REVIEW ROUTES - Hệ thống đánh giá (cần đăng nhập)
// ============================================================================

Route::middleware('auth')->group(function() {
    Route::post('/reviews', [ReviewController::class, 'store'])->name('reviews.store');
    Route::put('/reviews/{review}', [ReviewController::class, 'update'])->name('reviews.update');
    Route::delete('/reviews/{review}', [ReviewController::class, 'destroy'])->name('reviews.destroy');
    Route::get('/reviews/user', [ReviewController::class, 'getUserReviews'])->name('reviews.user');
    Route::post('/reviews/can-review', [ReviewController::class, 'canReview'])->name('reviews.can-review');
});

// Public review endpoints
Route::get('/products/{product}/reviews', [ReviewController::class, 'getProductReviews'])->name('products.reviews');

// ============================================================================
// WISHLIST ROUTES - Hệ thống danh sách yêu thích (cần đăng nhập)
// ============================================================================

Route::middleware('auth')->group(function() {
    // Wishlist pages
    Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist.index');
    
    // Wishlist actions
    Route::post('/wishlist', [WishlistController::class, 'store'])->name('wishlist.store');
    Route::delete('/wishlist/{product}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');
    
    // AJAX endpoints
    Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
    Route::get('/wishlist/count', [WishlistController::class, 'count'])->name('wishlist.count');
    Route::post('/wishlist/check', [WishlistController::class, 'check'])->name('wishlist.check');
});

// ============================================================================
// RECOMMENDATION ROUTES - Hệ thống gợi ý sản phẩm
// ============================================================================

// Public recommendation routes
Route::prefix('recommendations')->name('recommendations.')->group(function() {
    Route::get('/user', [RecommendationController::class, 'getUserRecommendations'])->name('user');
    Route::get('/product/{product}/similar', [RecommendationController::class, 'getSimilarProducts'])->name('similar');
    Route::get('/popular', [RecommendationController::class, 'getPopularProducts'])->name('popular');
    Route::get('/trending', [RecommendationController::class, 'getTrendingProducts'])->name('trending');
    Route::post('/load-more', [RecommendationController::class, 'loadMore'])->name('load_more');
});

// Authenticated recommendation routes  
Route::middleware('auth')->prefix('recommendations')->name('recommendations.')->group(function() {
    Route::delete('/cache/clear', [RecommendationController::class, 'clearCache'])->name('clear_cache');
});

// ============================================================================
// VOUCHER ROUTES - Hệ thống voucher giảm giá
// ============================================================================

// Public voucher routes
Route::prefix('vouchers')->name('vouchers.')->group(function() {
    Route::get('/', [VoucherController::class, 'index'])->name('index');
    Route::get('/{voucher}', [VoucherController::class, 'show'])->name('show');
    Route::post('/validate', [VoucherController::class, 'validateCode'])->name('validate');
    Route::get('/api/available', [VoucherController::class, 'getAvailableVouchers'])->name('available');
    Route::post('/api/calculate-savings', [VoucherController::class, 'calculateSavings'])->name('calculate-savings');
    Route::post('/cart/apply', [VoucherController::class, 'applyToCart'])->name('cart.apply');
    Route::delete('/cart/remove', [VoucherController::class, 'removeFromCart'])->name('cart.remove');
});

// Authenticated voucher routes
Route::middleware('auth')->prefix('vouchers')->name('vouchers.')->group(function() {
    Route::get('/my/history', [VoucherController::class, 'myVouchers'])->name('my-vouchers');
});

// ============================================================================
// CMS PAGES - Trang tĩnh
// ============================================================================

// Specific static pages
Route::get('/about', [PageController::class, 'about'])->name('pages.about');
Route::get('/contact', [PageController::class, 'contact'])->name('pages.contact');
Route::post('/contact', [PageController::class, 'submitContact'])->name('pages.contact.submit');
Route::get('/privacy', [PageController::class, 'privacy'])->name('pages.privacy');
Route::get('/terms', [PageController::class, 'terms'])->name('pages.terms');

// Dynamic pages (must be last)
Route::get('/pages', [PageController::class, 'index'])->name('pages.index');
Route::get('/pages/{slug}', [PageController::class, 'show'])->name('pages.show');

// ============================================================================
// ADMIN ROUTES - Quản trị viên
// ============================================================================

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function() {
    
    // Dashboard
    Route::get('/', [AdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/dashboard', [AdminController::class, 'dashboard'])->name('dashboard.index');
    
    // Reports
    Route::get('/reports', function() {
        return view('admin.reports.index');
    })->name('reports.index');
    
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
    
    // ========================================================================
    // ADMIN REVIEW MANAGEMENT - Quản lý đánh giá (Admin)
    // ========================================================================
    Route::prefix('reviews')->name('reviews.')->group(function() {
        Route::get('/', [AdminController::class, 'reviews'])->name('index');
        Route::patch('/{review}/approve', [AdminController::class, 'approveReview'])->name('approve');
        Route::patch('/{review}/reject', [AdminController::class, 'rejectReview'])->name('reject');
    });
    
    // ========================================================================
    // VOUCHER MANAGEMENT - Quản lý Voucher
    // ========================================================================
    Route::prefix('vouchers')->name('vouchers.')->group(function() {
        Route::get('/', [AdminVoucherController::class, 'index'])->name('index');
        Route::get('/create', [AdminVoucherController::class, 'create'])->name('create');
        Route::post('/', [AdminVoucherController::class, 'store'])->name('store');
        Route::get('/{voucher}', [AdminVoucherController::class, 'show'])->name('show');
        Route::get('/{voucher}/edit', [AdminVoucherController::class, 'edit'])->name('edit');
        Route::put('/{voucher}', [AdminVoucherController::class, 'update'])->name('update');
        Route::delete('/{voucher}', [AdminVoucherController::class, 'destroy'])->name('destroy');
        
        // Additional voucher actions
        Route::patch('/{voucher}/toggle-status', [AdminVoucherController::class, 'toggleStatus'])->name('toggle-status');
        Route::post('/bulk-action', [AdminVoucherController::class, 'bulkAction'])->name('bulk-action');
        Route::get('/export/csv', [AdminVoucherController::class, 'export'])->name('export');
        Route::get('/api/statistics', [AdminVoucherController::class, 'statistics'])->name('api.statistics');
    });
    
    // ========================================================================
    // CHART APIs - API biểu đồ
    // ========================================================================
    Route::prefix('api/charts')->name('charts.')->group(function() {
        Route::get('/daily-revenue', [ChartController::class, 'dailyRevenue'])->name('daily-revenue');
        Route::get('/monthly-revenue', [ChartController::class, 'monthlyRevenue'])->name('monthly-revenue');
        Route::get('/orders-by-status', [ChartController::class, 'ordersByStatus'])->name('orders-by-status');
        Route::get('/top-products', [ChartController::class, 'topProducts'])->name('top-products');
        Route::get('/category-stats', [ChartController::class, 'categoryStats'])->name('category-stats');
        Route::get('/statistics', [ChartController::class, 'statistics'])->name('statistics');
    });
    
    // ========================================================================
    // CMS PAGES - Quản lý nội dung
    // ========================================================================
    Route::prefix('pages')->name('pages.')->group(function() {
        // Static routes không có parameters
        Route::get('/', [AdminPageController::class, 'index'])->name('index');
        Route::get('/create', [AdminPageController::class, 'create'])->name('create');
        Route::post('/', [AdminPageController::class, 'store'])->name('store');
        Route::post('/bulk-action', [AdminPageController::class, 'bulkAction'])->name('bulk-action');
        Route::get('/api/statistics', [AdminPageController::class, 'getStatistics'])->name('api.statistics');
        
        // Explicit routes for specific actions (must come before generic {page} routes)
        Route::put('/toggle-status/{page}', [AdminPageController::class, 'toggleStatus'])->name('toggle-status')->where('page', '[0-9]+');
        Route::get('/preview/{page}', [AdminPageController::class, 'preview'])->name('preview')->where('page', '[0-9]+');
        Route::post('/duplicate/{page}', [AdminPageController::class, 'duplicate'])->name('duplicate')->where('page', '[0-9]+');
        Route::get('/edit/{page}', [AdminPageController::class, 'edit'])->name('edit')->where('page', '[0-9]+');
        
        // Basic CRUD routes last
        Route::get('/{page}', [AdminPageController::class, 'show'])->name('show')->where('page', '[0-9]+');
        Route::put('/{page}', [AdminPageController::class, 'update'])->name('update')->where('page', '[0-9]+');
        Route::delete('/{page}', [AdminPageController::class, 'destroy'])->name('destroy')->where('page', '[0-9]+');
    });
    
    // ========================================================================
    // REPORTS - Báo cáo
    // ========================================================================
    Route::prefix('reports')->name('reports.')->group(function() {
        Route::get('/', function() {
            return view('admin.reports.index');
        })->name('index');
    });
    
});