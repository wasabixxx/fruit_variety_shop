<!-- Admin Sidebar Component -->
<div class="admin-sidebar">
    <div class="admin-sidebar-header">
        <div class="d-flex align-items-center">
            <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-3" 
                 style="width: 40px; height: 40px;">
                <i class="bi bi-shop text-white"></i>
            </div>
            <div>
                <h6 class="mb-0 text-white fw-bold">Fruit Shop</h6>
                <small class="text-white-50">Admin Panel</small>
            </div>
        </div>
    </div>
    
    <nav class="admin-sidebar-nav">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                   href="{{ route('admin.dashboard') }}">
                    <i class="bi bi-speedometer2"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.categories.*') ? 'active' : '' }}" 
                   href="{{ route('admin.categories.index') }}">
                    <i class="bi bi-tags"></i>
                    <span>Quản lý Danh mục</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.products.*') ? 'active' : '' }}" 
                   href="{{ route('admin.products.index') }}">
                    <i class="bi bi-box"></i>
                    <span>Quản lý Sản phẩm</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}" 
                   href="{{ route('admin.users.index') }}">
                    <i class="bi bi-people"></i>
                    <span>Quản lý User</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.orders.*') ? 'active' : '' }}" 
                   href="{{ route('admin.orders.index') }}">
                    <i class="bi bi-bag-check"></i>
                    <span>Quản lý Đơn hàng</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.vouchers.*') ? 'active' : '' }}" 
                   href="{{ route('admin.vouchers.index') }}">
                    <i class="bi bi-ticket-perforated"></i>
                    <span>Quản lý Voucher</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}" 
                   href="{{ route('admin.reports.index') }}">
                    <i class="bi bi-graph-up"></i>
                    <span>Báo cáo</span>
                </a>
            </li>
        </ul>
    </nav>
    
    <div class="admin-sidebar-footer">
        <div class="d-flex align-items-center text-white-50">
            <div class="bg-white bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2"
                 style="width: 30px; height: 30px;">
                <i class="bi bi-person text-white"></i>
            </div>
            <div>
                <small class="d-block">{{ auth()->user()->name ?? 'Admin' }}</small>
                <form action="{{ route('logout') }}" method="POST" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-link p-0 text-white-50" style="font-size: 0.75rem;">
                        Đăng xuất
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
