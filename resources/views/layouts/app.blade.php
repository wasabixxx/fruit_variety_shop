<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Fruit Variety Shop - Trái cây tươi ngon')</title>
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://lh3.googleusercontent.com/ogw/AF2bZyj9JRJYMr7QxX0u2AEwLgMVwA1quq0Uxbo0_ESRTW8Zhao=s32-c-mo" rel="icon">
    
    <!-- Custom Styles -->
    <style>
        :root {
            --primary: #FF6B35;
            --primary-light: #FF8A65;
            --primary-dark: #E55722;
            --secondary: #2ECC71;
            --secondary-light: #58D68D;
            --secondary-dark: #27AE60;
            --accent: #3498DB;
            --dark: #2C3E50;
            --light: #ECF0F1;
            --gray: #95A5A6;
            --gray-light: #BDC3C7;
            --white: #FFFFFF;
            --danger: #E74C3C;
            --warning: #F39C12;
            --success: #27AE60;
            --info: #3498DB;
            
            --gradient-primary: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            --gradient-secondary: linear-gradient(135deg, var(--secondary) 0%, var(--secondary-dark) 100%);
            --gradient-accent: linear-gradient(135deg, var(--accent) 0%, #2980B9 100%);
            
            --shadow-sm: 0 2px 4px rgba(0,0,0,0.06);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.1);
            --shadow-lg: 0 8px 24px rgba(0,0,0,0.12);
            --shadow-xl: 0 12px 32px rgba(0,0,0,0.16);
            
            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 16px;
            --radius-xl: 24px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            font-weight: 400;
            line-height: 1.6;
            color: var(--dark);
            background: #FAFBFC;
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        /* Navigation */
        .navbar {
            background: var(--white) !important;
            backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(0,0,0,0.05);
            box-shadow: var(--shadow-sm);
            padding: 1rem 0;
            position: sticky;
            top: 0;
            z-index: 1000;
        }
        
        .navbar-brand {
            font-weight: 800;
            font-size: 1.5rem;
            background: var(--gradient-primary);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            text-decoration: none !important;
        }
        
        .navbar-nav .nav-link {
            font-weight: 500;
            color: var(--dark) !important;
            padding: 0.75rem 1.25rem !important;
            border-radius: var(--radius-md);
            transition: all 0.3s ease;
            position: relative;
        }
        
        .navbar-nav .nav-link:hover {
            background: rgba(255, 107, 53, 0.08);
            color: var(--primary) !important;
        }
        
        .navbar-nav .nav-link.active {
            background: var(--gradient-primary);
            color: var(--white) !important;
            box-shadow: var(--shadow-md);
        }
        
        /* Cart Badge & Wishlist Badge */
        .cart-badge, .wishlist-badge {
            position: absolute;
            top: -8px;
            right: -8px;
            background: var(--gradient-primary);
            color: var(--white);
            border-radius: 50%;
            width: 24px;
            height: 24px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.75rem;
            font-weight: 600;
            box-shadow: var(--shadow-md);
            animation: pulse 2s infinite;
        }
        
        .wishlist-badge {
            background: linear-gradient(135deg, #E74C3C 0%, #C0392B 100%);
        }
        
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }
        
        /* Buttons */
        .btn {
            font-weight: 600;
            border-radius: var(--radius-md);
            padding: 0.75rem 2rem;
            border: none;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }
        
        .btn-primary {
            background: var(--gradient-primary);
            color: var(--white);
            box-shadow: var(--shadow-md);
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
            background: var(--gradient-primary);
            color: var(--white);
        }
        
        .btn-secondary {
            background: var(--gradient-secondary);
            color: var(--white);
            box-shadow: var(--shadow-md);
        }
        
        .btn-secondary:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
            background: var(--gradient-secondary);
            color: var(--white);
        }
        
        .btn-outline-primary {
            background: transparent;
            color: var(--primary);
            border: 2px solid var(--primary);
        }
        
        .btn-outline-primary:hover {
            background: var(--primary);
            color: var(--white);
            transform: translateY(-2px);
            box-shadow: var(--shadow-md);
        }
        
        .btn-lg {
            padding: 1rem 2.5rem;
            font-size: 1.1rem;
            border-radius: var(--radius-lg);
        }
        
        .btn-sm {
            padding: 0.5rem 1rem;
            font-size: 0.875rem;
            border-radius: var(--radius-sm);
        }
        
        /* Cards */
        .card {
            background: var(--white);
            border: none;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            transition: all 0.3s ease;
            overflow: hidden;
        }
        
        .card:hover {
            box-shadow: var(--shadow-lg);
            transform: translateY(-4px);
        }
        
        .card-header {
            background: transparent;
            border-bottom: 1px solid rgba(0,0,0,0.05);
            padding: 1.5rem;
            font-weight: 600;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        /* Forms */
        .form-control, .form-select {
            border: 2px solid rgba(0,0,0,0.08);
            border-radius: var(--radius-md);
            padding: 0.875rem 1rem;
            font-weight: 500;
            transition: all 0.3s ease;
            background: var(--white);
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary);
            box-shadow: 0 0 0 4px rgba(255, 107, 53, 0.1);
        }
        
        .form-label {
            font-weight: 600;
            color: var(--dark);
            margin-bottom: 0.5rem;
        }
        
        /* Alerts */
        .alert {
            border: none;
            border-radius: var(--radius-md);
            padding: 1rem 1.5rem;
            font-weight: 500;
        }
        
        .alert-success {
            background: rgba(39, 174, 96, 0.1);
            color: var(--success);
            border-left: 4px solid var(--success);
        }
        
        .alert-danger {
            background: rgba(231, 76, 60, 0.1);
            color: var(--danger);
            border-left: 4px solid var(--danger);
        }
        
        .alert-warning {
            background: rgba(243, 156, 18, 0.1);
            color: var(--warning);
            border-left: 4px solid var(--warning);
        }
        
        .alert-info {
            background: rgba(52, 152, 219, 0.1);
            color: var(--info);
            border-left: 4px solid var(--info);
        }
        
        /* Badges */
        .badge {
            font-weight: 600;
            padding: 0.375rem 0.75rem;
            border-radius: var(--radius-sm);
        }
        
        /* Dropdown */
        .dropdown-menu {
            border: none;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-lg);
            padding: 0.5rem;
            margin-top: 0.5rem;
            background: var(--white);
        }
        
        .dropdown-item {
            border-radius: var(--radius-sm);
            padding: 0.75rem 1rem;
            font-weight: 500;
            transition: all 0.2s ease;
            color: var(--dark);
        }
        
        .dropdown-item:hover {
            background: rgba(255, 107, 53, 0.08);
            color: var(--primary);
        }
        
        /* Footer Hover Effects */
        .hover-text-white:hover {
            color: white !important;
            transition: color 0.2s ease;
        }
        
        /* Page Content */
        .main-content {
            min-height: calc(100vh - 120px);
            padding: 2rem 0;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .navbar-nav .nav-link {
                padding: 0.5rem 1rem !important;
            }
            
            .btn {
                padding: 0.625rem 1.5rem;
            }
            
            .btn-lg {
                padding: 0.875rem 2rem;
            }
            
            .card-body {
                padding: 1rem;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="/">
                <i class="bi bi-flower1 me-2"></i>Fruit Variety Shop
            </a>
            
            <button class="navbar-toggler border-0" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="bi bi-list" style="font-size: 1.5rem; color: var(--dark);"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('/') ? 'active' : '' }}" href="/">
                            <i class="bi bi-house me-1"></i>Trang chủ
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('categories*') ? 'active' : '' }}" href="{{ route('categories.index') }}">
                            <i class="bi bi-grid-3x3-gap me-1"></i>Danh mục
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('products*') ? 'active' : '' }}" href="{{ route('products.index') }}">
                            <i class="bi bi-bag me-1"></i>Sản phẩm
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::is('vouchers') ? 'active' : '' }}" href="{{ route('vouchers.index') }}">
                            <i class="bi bi-ticket-perforated me-1"></i>Voucher
                        </a>
                    </li>
                </ul>
                
                <ul class="navbar-nav ms-auto align-items-center">
                    <!-- Wishlist -->
                    @auth
                        <li class="nav-item me-2">
                            <a class="nav-link position-relative p-2" href="{{ route('wishlist.index') }}" title="Danh sách yêu thích">
                                <i class="bi bi-heart" style="font-size: 1.5rem;"></i>
                                <span class="wishlist-badge d-none">0</span>
                            </a>
                        </li>
                    @endauth
                    
                    <!-- Cart -->
                    <li class="nav-item me-2">
                        <a class="nav-link position-relative p-2" href="{{ route('cart.index') }}">
                            <i class="bi bi-cart4" style="font-size: 1.5rem;"></i>
                            @php $cartCount = is_array(session('cart')) ? collect(session('cart'))->sum('quantity') : 0; @endphp
                            @if($cartCount > 0)
                                <span class="cart-badge">{{ $cartCount }}</span>
                            @endif
                        </a>
                    </li>
                    
                    @auth
                        <!-- Admin Panel -->
                        @if(auth()->user()->isAdmin())
                            <li class="nav-item me-2">
                                <a class="nav-link {{ Request::is('admin*') ? 'active' : '' }}" href="{{ route('admin.dashboard') }}">
                                    <i class="bi bi-speedometer2 me-1"></i>Admin Panel
                                </a>
                            </li>
                        @endif
                        
                        <!-- User Dropdown -->
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" role="button" data-bs-toggle="dropdown">
                                <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center me-2" 
                                     style="width: 32px; height: 32px; background: var(--gradient-primary) !important;">
                                    <i class="bi bi-person text-white"></i>
                                </div>
                                {{ auth()->user()->name }}
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li>
                                    <a class="dropdown-item" href="{{ route('orders.my-orders') }}">
                                        <i class="bi bi-clock-history me-2"></i>Lịch sử đơn hàng
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="{{ route('vouchers.my-vouchers') }}">
                                        <i class="bi bi-ticket-perforated me-2"></i>Voucher của tôi
                                    </a>
                                </li>
                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="bi bi-box-arrow-right me-2"></i>Đăng xuất
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </li>
                    @else
                        <!-- Guest Links -->
                        <li class="nav-item me-2">
                            <a class="btn btn-outline-primary btn-sm" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right me-1"></i>Đăng nhập
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary btn-sm" href="{{ route('register') }}">
                                <i class="bi bi-person-plus me-1"></i>Đăng ký
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>
    
    <div class="main-content">
        <div class="container">
            <!-- Email Verification Notice -->
            @auth
                @if(!auth()->user()->isAdmin() && !auth()->user()->hasVerifiedEmail())
                    <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-envelope-exclamation fs-5 me-3"></i>
                            <div class="flex-grow-1">
                                <strong>Xác nhận email:</strong> 
                                Vui lòng kiểm tra email để xác nhận tài khoản của bạn.
                                <a href="{{ route('email.verification.notice') }}" class="alert-link fw-semibold">Xem chi tiết</a>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                @endif
            @endauth
            
            <!-- Session Messages -->
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle fs-5 me-3"></i>
                        <div class="flex-grow-1">{{ session('success') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if(session('error'))
                <div class="alert alert-danger alert-dismissible fade show mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle fs-5 me-3"></i>
                        <div class="flex-grow-1">{{ session('error') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if(session('warning'))
                <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle fs-5 me-3"></i>
                        <div class="flex-grow-1">{{ session('warning') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if(session('info'))
                <div class="alert alert-info alert-dismissible fade show mb-4" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-info-circle fs-5 me-3"></i>
                        <div class="flex-grow-1">{{ session('info') }}</div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @yield('content')
        </div>
    </div>
    
    <!-- Footer -->
    <footer class="bg-dark text-white py-5 mt-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="fw-bold mb-3 text-white">
                        <i class="bi bi-flower1 me-2 text-primary"></i>Fruit Variety Shop
                    </h5>
                    <p class="text-white-50">Chuyên cung cấp các loại hạt trái cây tươi ngon, chất lượng cao từ các vùng trồng uy tín trên toàn quốc.</p>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-primary"><i class="bi bi-facebook fs-5"></i></a>
                        <a href="#" class="text-primary"><i class="bi bi-instagram fs-5"></i></a>
                        <a href="#" class="text-primary"><i class="bi bi-twitter fs-5"></i></a>
                        <a href="#" class="text-primary"><i class="bi bi-youtube fs-5"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="fw-semibold mb-3 text-white">Sản phẩm</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="{{ route('categories.index') }}" class="text-decoration-none text-white-50 hover-text-white">Danh mục</a></li>
                        <li class="mb-2"><a href="{{ route('products.index') }}" class="text-decoration-none text-white-50 hover-text-white">Tất cả sản phẩm</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-white-50 hover-text-white">Trái cây nhập khẩu</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-white-50 hover-text-white">Trái cây trong nước</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="fw-semibold mb-3 text-white">Hỗ trợ</h6>
                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="#" class="text-decoration-none text-white-50 hover-text-white">Liên hệ</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-white-50 hover-text-white">Hỏi đáp</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-white-50 hover-text-white">Chính sách đổi trả</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-white-50 hover-text-white">Vận chuyển</a></li>
                    </ul>
                </div>
                <div class="col-lg-4 mb-4">
                    <h6 class="fw-semibold mb-3 text-white">Liên hệ</h6>
                    <div class="text-white-50">
                        <div class="mb-2">
                            <i class="bi bi-geo-alt me-2"></i>123 Đường ABC, Quận 1, TP.HCM
                        </div>
                        <div class="mb-2">
                            <i class="bi bi-telephone me-2"></i>0886 345 204
                        </div>
                        <div class="mb-2">
                            <i class="bi bi-envelope me-2"></i>fruitvarietyshop@gmail.com
                        </div>
                        <div class="mb-2">
                            <i class="bi bi-clock me-2"></i>8:00 - 20:00 (Thứ 2 - Chủ nhật)
                        </div>
                    </div>
                </div>
            </div>
            <hr class="my-4 border-secondary">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <p class="mb-0 text-white-50">&copy; 2025 Fruit Variety Shop. Tất cả quyền được bảo lưu.</p>
                </div>
                <div class="col-md-6 text-md-end">
                    <div class="d-flex justify-content-md-end gap-3 align-items-center">
                        <img src="https://itviec.com/rails/active_storage/representations/proxy/eyJfcmFpbHMiOnsiZGF0YSI6MjA0NjgzMiwicHVyIjoiYmxvYl9pZCJ9fQ==--6d1081fa86f1300daa38e2cb2fd3ffc5a28b6592/eyJfcmFpbHMiOnsiZGF0YSI6eyJmb3JtYXQiOiJwbmciLCJyZXNpemVfdG9fbGltaXQiOlszMDAsMzAwXX0sInB1ciI6InZhcmlhdGlvbiJ9fQ==--e1d036817a0840c585f202e70291f5cdd058753d/MoMo%20Logo.png" 
                             alt="MoMo" style="height: 24px;" class="opacity-75">
                        <i class="bi bi-credit-card fs-5 text-white-50"></i>
                        <i class="bi bi-bank fs-5 text-white-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Recommendation System -->
    <script>
        // Global variables for recommendation system
        window.isAuthenticated = {{ auth()->check() ? 'true' : 'false' }};
    </script>
    <script src="{{ asset('js/recommendations.js') }}"></script>
    
    <!-- Wishlist count loader -->
    @auth
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Load wishlist count on page load
            loadWishlistCount();
            
            function loadWishlistCount() {
                fetch('/wishlist/count', {
                    method: 'GET',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        updateWishlistBadge(data.count);
                    }
                })
                .catch(error => {
                    console.error('Error loading wishlist count:', error);
                });
            }
            
            function updateWishlistBadge(count) {
                const badge = document.querySelector('.wishlist-badge');
                if (badge) {
                    badge.textContent = count;
                    if (count > 0) {
                        badge.classList.remove('d-none');
                    } else {
                        badge.classList.add('d-none');
                    }
                }
            }
            
            // Global function to update wishlist count from other scripts
            window.updateWishlistCount = function(newCount) {
                updateWishlistBadge(newCount);
            };
        });
    </script>
    @endauth
    
    @stack('scripts')
</body>
</html>