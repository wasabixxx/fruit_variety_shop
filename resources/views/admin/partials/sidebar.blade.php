<!-- Sidebar Header -->
<div class="sidebar-header">
    <h3><i class="bi bi-shop me-2"></i>Fruit Shop</h3>
</div>

<!-- Navigation Menu -->
<nav class="nav flex-column">
    <div class="nav-item">
        <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="bi bi-speedometer2"></i>
            Dashboard
        </a>
    </div>
    
    <div class="nav-item">
        <a href="{{ route('admin.categories.index') }}" class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}">
            <i class="bi bi-tags"></i>
            Danh mục
        </a>
    </div>
    
    <div class="nav-item">
        <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}">
            <i class="bi bi-box-seam"></i>
            Sản phẩm
        </a>
    </div>
    
    <div class="nav-item">
        <a href="{{ route('admin.reports.index') }}" class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}">
            <i class="bi bi-graph-up"></i>
            Báo cáo
        </a>
    </div>
    
    <div class="nav-item">
        <a href="{{ route('admin.orders.index') }}" class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}">
            <i class="bi bi-cart-check"></i>
            Đơn hàng
        </a>
    </div>
    
    <div class="nav-item">
        <a href="{{ route('admin.users.index') }}" class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}">
            <i class="bi bi-people"></i>
            Người dùng
        </a>
    </div>
    
    <hr class="sidebar-divider" style="border-color: rgba(255,255,255,0.1); margin: 1rem;">
    
    <div class="nav-item">
        <a href="{{ route('admin.settings') }}" class="nav-link {{ request()->routeIs('admin.settings') ? 'active' : '' }}">
            <i class="bi bi-gear"></i>
            Cài đặt
        </a>
    </div>
    
    <div class="nav-item">
        <form action="{{ route('logout') }}" method="POST" style="margin: 0;">
            @csrf
            <button type="submit" class="nav-link" style="background: none; border: none; width: 100%; text-align: left;">
                <i class="bi bi-box-arrow-right"></i>
                Đăng xuất
            </button>
        </form>
    </div>
</nav>
