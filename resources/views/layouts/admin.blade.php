<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Admin Panel - Fruit Variety Shop')</title>
    
    <!-- CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://lh3.googleusercontent.com/ogw/AF2bZyj9JRJYMr7QxX0u2AEwLgMVwA1quq0Uxbo0_ESRTW8Zhao=s32-c-mo" rel="icon">
    
    <!-- Admin Styles -->
    <style>
        :root {
            --admin-primary: #6366F1;
            --admin-primary-light: #818CF8;
            --admin-primary-dark: #4F46E5;
            --admin-secondary: #10B981;
            --admin-accent: #F59E0B;
            --admin-danger: #EF4444;
            --admin-warning: #F59E0B;
            --admin-info: #06B6D4;
            --admin-success: #10B981;
            --admin-dark: #1F2937;
            --admin-light: #F9FAFB;
            --admin-gray: #6B7280;
            --admin-white: #FFFFFF;
            
            --admin-sidebar-width: 280px;
            --admin-header-height: 72px;
            
            --shadow-xs: 0 1px 2px rgba(0,0,0,0.05);
            --shadow-sm: 0 2px 4px rgba(0,0,0,0.06);
            --shadow-md: 0 4px 12px rgba(0,0,0,0.1);
            --shadow-lg: 0 8px 24px rgba(0,0,0,0.12);
            --shadow-xl: 0 12px 32px rgba(0,0,0,0.16);
            
            --radius-sm: 6px;
            --radius-md: 8px;
            --radius-lg: 12px;
            --radius-xl: 16px;
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
            color: var(--admin-dark);
            background: var(--admin-light);
            -webkit-font-smoothing: antialiased;
            -moz-osx-font-smoothing: grayscale;
        }
        
        /* Admin Layout */
        .admin-wrapper {
            display: flex;
            min-height: 100vh;
        }
        
        /* Sidebar */
        .admin-sidebar {
            width: var(--admin-sidebar-width);
            background: var(--admin-white);
            border-right: 1px solid #E5E7EB;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
            overflow-y: auto;
            z-index: 1000;
            transform: translateX(-100%);
            transition: transform 0.3s ease;
        }
        
        .admin-sidebar.show {
            transform: translateX(0);
        }
        
        @media (min-width: 992px) {
            .admin-sidebar {
                position: relative;
                transform: translateX(0);
            }
            
            .admin-main {
                margin-left: 0;
                width: calc(100% - var(--admin-sidebar-width));
            }
        }
        
        .admin-sidebar-header {
            padding: 1.5rem;
            border-bottom: 1px solid #E5E7EB;
        }
        
        .admin-sidebar-brand {
            font-size: 1.25rem;
            font-weight: 700;
            color: var(--admin-primary);
            text-decoration: none;
            display: flex;
            align-items: center;
        }
        
        .admin-sidebar-nav {
            padding: 1rem 0;
        }
        
        .admin-nav-item {
            margin: 0.25rem 1rem;
        }
        
        .admin-nav-link {
            display: flex;
            align-items: center;
            padding: 0.875rem 1rem;
            color: var(--admin-gray);
            text-decoration: none;
            border-radius: var(--radius-md);
            font-weight: 500;
            transition: all 0.2s ease;
        }
        
        .admin-nav-link:hover {
            background: rgba(99, 102, 241, 0.05);
            color: var(--admin-primary);
        }
        
        .admin-nav-link.active {
            background: rgba(99, 102, 241, 0.1);
            color: var(--admin-primary);
            font-weight: 600;
        }
        
        .admin-nav-icon {
            width: 20px;
            margin-right: 0.75rem;
            text-align: center;
        }
        
        /* Main Content */
        .admin-main {
            flex: 1;
            margin-left: 0;
            transition: margin-left 0.3s ease;
        }
        
        @media (min-width: 992px) {
            .admin-main {
                margin-left: var(--admin-sidebar-width);
            }
        }
        
        /* Header */
        .admin-header {
            background: var(--admin-white);
            border-bottom: 1px solid #E5E7EB;
            height: var(--admin-header-height);
            padding: 0 1.5rem;
            display: flex;
            align-items: center;
            justify-content: between;
            position: sticky;
            top: 0;
            z-index: 999;
        }
        
        .admin-header-left {
            display: flex;
            align-items: center;
        }
        
        .admin-header-toggle {
            background: none;
            border: none;
            color: var(--admin-gray);
            font-size: 1.25rem;
            margin-right: 1rem;
            padding: 0.5rem;
            border-radius: var(--radius-md);
            transition: all 0.2s ease;
        }
        
        .admin-header-toggle:hover {
            background: rgba(99, 102, 241, 0.05);
            color: var(--admin-primary);
        }
        
        @media (min-width: 992px) {
            .admin-header-toggle {
                display: none;
            }
        }
        
        .admin-breadcrumb {
            margin: 0;
            background: transparent;
            padding: 0;
        }
        
        .admin-breadcrumb .breadcrumb-item {
            font-weight: 500;
        }
        
        .admin-breadcrumb .breadcrumb-item.active {
            color: var(--admin-primary);
        }
        
        .admin-header-right {
            display: flex;
            align-items: center;
            margin-left: auto;
        }
        
        /* Content */
        .admin-content {
            padding: 2rem;
            min-height: calc(100vh - var(--admin-header-height));
        }
        
        /* Cards */
        .admin-card {
            background: var(--admin-white);
            border: none;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            transition: all 0.2s ease;
        }
        
        .admin-card:hover {
            box-shadow: var(--shadow-md);
        }
        
        .admin-card-header {
            background: transparent;
            border-bottom: 1px solid #E5E7EB;
            padding: 1.5rem;
            font-weight: 600;
            display: flex;
            align-items: center;
            justify-content: between;
        }
        
        .admin-card-body {
            padding: 1.5rem;
        }
        
        /* Stats Cards */
        .admin-stats-card {
            background: var(--admin-white);
            border: none;
            border-radius: var(--radius-lg);
            box-shadow: var(--shadow-sm);
            transition: all 0.2s ease;
            position: relative;
            overflow: hidden;
        }
        
        .admin-stats-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            width: 4px;
            height: 100%;
            background: var(--admin-primary);
        }
        
        .admin-stats-card.success::before {
            background: var(--admin-success);
        }
        
        .admin-stats-card.warning::before {
            background: var(--admin-warning);
        }
        
        .admin-stats-card.danger::before {
            background: var(--admin-danger);
        }
        
        .admin-stats-card:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow-lg);
        }
        
        /* Buttons */
        .btn-admin-primary {
            background: var(--admin-primary);
            border-color: var(--admin-primary);
            color: white;
            font-weight: 600;
            border-radius: var(--radius-md);
            padding: 0.5rem 1rem;
            transition: all 0.2s ease;
        }
        
        .btn-admin-primary:hover {
            background: var(--admin-primary-dark);
            border-color: var(--admin-primary-dark);
            color: white;
            transform: translateY(-1px);
            box-shadow: var(--shadow-md);
        }
        
        /* Tables */
        .admin-table {
            border-radius: var(--radius-lg);
            overflow: hidden;
            box-shadow: var(--shadow-sm);
        }
        
        .admin-table thead th {
            background: #F8FAFC;
            border: none;
            font-weight: 600;
            color: var(--admin-dark);
            padding: 1rem;
        }
        
        .admin-table tbody td {
            border-color: #E5E7EB;
            padding: 1rem;
            vertical-align: middle;
        }
        
        .admin-table tbody tr:hover {
            background: rgba(99, 102, 241, 0.02);
        }
        
        /* Forms */
        .form-control-admin {
            border: 2px solid #E5E7EB;
            border-radius: var(--radius-md);
            padding: 0.75rem 1rem;
            font-weight: 500;
            transition: all 0.2s ease;
        }
        
        .form-control-admin:focus {
            border-color: var(--admin-primary);
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
        }
        
        /* Alerts */
        .alert-admin {
            border: none;
            border-radius: var(--radius-md);
            padding: 1rem 1.5rem;
            font-weight: 500;
            border-left: 4px solid;
        }
        
        .alert-admin-success {
            background: rgba(16, 185, 129, 0.1);
            color: var(--admin-success);
            border-left-color: var(--admin-success);
        }
        
        .alert-admin-danger {
            background: rgba(239, 68, 68, 0.1);
            color: var(--admin-danger);
            border-left-color: var(--admin-danger);
        }
        
        .alert-admin-warning {
            background: rgba(245, 158, 11, 0.1);
            color: var(--admin-warning);
            border-left-color: var(--admin-warning);
        }
        
        .alert-admin-info {
            background: rgba(6, 182, 212, 0.1);
            color: var(--admin-info);
            border-left-color: var(--admin-info);
        }
        
        /* Badges */
        .badge-admin-primary {
            background: rgba(99, 102, 241, 0.1);
            color: var(--admin-primary);
            border: 1px solid rgba(99, 102, 241, 0.2);
        }
        
        .badge-admin-success {
            background: rgba(16, 185, 129, 0.1);
            color: var(--admin-success);
            border: 1px solid rgba(16, 185, 129, 0.2);
        }
        
        .badge-admin-warning {
            background: rgba(245, 158, 11, 0.1);
            color: var(--admin-warning);
            border: 1px solid rgba(245, 158, 11, 0.2);
        }
        
        .badge-admin-danger {
            background: rgba(239, 68, 68, 0.1);
            color: var(--admin-danger);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }
        
        /* Sidebar Overlay */
        .admin-sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 999;
            display: none;
        }
        
        .admin-sidebar-overlay.show {
            display: block;
        }
        
        @media (min-width: 992px) {
            .admin-sidebar-overlay {
                display: none !important;
            }
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .admin-content {
                padding: 1rem;
            }
            
            .admin-header {
                padding: 0 1rem;
            }
        }
    </style>
</head>
<body>
    <div class="admin-wrapper">
        <!-- Sidebar -->
        <aside class="admin-sidebar" id="adminSidebar">
            <div class="admin-sidebar-header">
                <a href="{{ route('admin.dashboard') }}" class="admin-sidebar-brand">
                    <i class="bi bi-speedometer2 me-2"></i>
                    Admin Panel
                </a>
            </div>
            
            <nav class="admin-sidebar-nav">
                <div class="admin-nav-item">
                    <a href="{{ route('admin.dashboard') }}" 
                       class="admin-nav-link {{ Request::is('admin') || Request::is('admin/dashboard') ? 'active' : '' }}">
                        <i class="bi bi-house admin-nav-icon"></i>
                        Dashboard
                    </a>
                </div>
                
                <div class="admin-nav-item">
                    <a href="{{ route('admin.orders') }}" 
                       class="admin-nav-link {{ Request::is('admin/orders*') ? 'active' : '' }}">
                        <i class="bi bi-cart admin-nav-icon"></i>
                        Quản lý đơn hàng
                    </a>
                </div>
                
                <div class="admin-nav-item">
                    <a href="{{ route('admin.users') }}" 
                       class="admin-nav-link {{ Request::is('admin/users*') ? 'active' : '' }}">
                        <i class="bi bi-people admin-nav-icon"></i>
                        Quản lý người dùng
                    </a>
                </div>
                
                <div class="admin-nav-item">
                    <a href="{{ route('categories.index') }}" 
                       class="admin-nav-link {{ Request::is('categories*') ? 'active' : '' }}">
                        <i class="bi bi-grid-3x3-gap admin-nav-icon"></i>
                        Danh mục sản phẩm
                    </a>
                </div>
                
                <div class="admin-nav-item">
                    <a href="{{ route('products.index') }}" 
                       class="admin-nav-link {{ Request::is('products*') ? 'active' : '' }}">
                        <i class="bi bi-box admin-nav-icon"></i>
                        Sản phẩm
                    </a>
                </div>
                
                <hr class="my-3 mx-4">
                
                <div class="admin-nav-item">
                    <a href="/" class="admin-nav-link">
                        <i class="bi bi-globe admin-nav-icon"></i>
                        Xem website
                    </a>
                </div>
                
                <div class="admin-nav-item">
                    <form action="{{ route('logout') }}" method="POST" class="d-inline w-100">
                        @csrf
                        <button type="submit" class="admin-nav-link w-100 text-start border-0 bg-transparent text-danger">
                            <i class="bi bi-box-arrow-right admin-nav-icon"></i>
                            Đăng xuất
                        </button>
                    </form>
                </div>
            </nav>
        </aside>
        
        <!-- Main Content -->
        <main class="admin-main">
            <!-- Header -->
            <header class="admin-header">
                <div class="admin-header-left">
                    <button class="admin-header-toggle" onclick="toggleSidebar()">
                        <i class="bi bi-list"></i>
                    </button>
                    
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb admin-breadcrumb">
                            @yield('breadcrumb')
                        </ol>
                    </nav>
                </div>
                
                <div class="admin-header-right">
                    <div class="dropdown">
                        <button class="btn btn-outline-secondary dropdown-toggle rounded-circle" 
                                type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-person"></i>
                        </button>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li>
                                <span class="dropdown-item-text">
                                    <strong>{{ auth()->user()->name }}</strong>
                                    <small class="d-block text-muted">{{ auth()->user()->email }}</small>
                                </span>
                            </li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <a class="dropdown-item" href="/">
                                    <i class="bi bi-globe me-2"></i>Xem website
                                </a>
                            </li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>Đăng xuất
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </header>
            
            <!-- Content -->
            <div class="admin-content">
                <!-- Session Messages -->
                @if(session('success'))
                    <div class="alert alert-admin alert-admin-success alert-dismissible fade show mb-4" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle fs-5 me-3"></i>
                            <div class="flex-grow-1">{{ session('success') }}</div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if(session('error'))
                    <div class="alert alert-admin alert-admin-danger alert-dismissible fade show mb-4" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle fs-5 me-3"></i>
                            <div class="flex-grow-1">{{ session('error') }}</div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if(session('warning'))
                    <div class="alert alert-admin alert-admin-warning alert-dismissible fade show mb-4" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-exclamation-triangle fs-5 me-3"></i>
                            <div class="flex-grow-1">{{ session('warning') }}</div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @if(session('info'))
                    <div class="alert alert-admin alert-admin-info alert-dismissible fade show mb-4" role="alert">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-info-circle fs-5 me-3"></i>
                            <div class="flex-grow-1">{{ session('info') }}</div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif
                
                @yield('content')
            </div>
        </main>
        
        <!-- Sidebar Overlay -->
        <div class="admin-sidebar-overlay" id="adminSidebarOverlay" onclick="toggleSidebar()"></div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleSidebar() {
            const sidebar = document.getElementById('adminSidebar');
            const overlay = document.getElementById('adminSidebarOverlay');
            
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        }
        
        // Close sidebar when clicking outside on mobile
        document.addEventListener('click', function(event) {
            const sidebar = document.getElementById('adminSidebar');
            const toggle = document.querySelector('.admin-header-toggle');
            
            if (window.innerWidth < 992) {
                if (!sidebar.contains(event.target) && !toggle.contains(event.target)) {
                    sidebar.classList.remove('show');
                    document.getElementById('adminSidebarOverlay').classList.remove('show');
                }
            }
        });
    </script>
    
    @yield('scripts')
</body>
</html>
