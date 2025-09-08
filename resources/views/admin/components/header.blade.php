<!-- Admin Header Component -->
<div class="admin-header">
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <button class="btn btn-link text-dark d-lg-none me-3" id="sidebarToggle">
                    <i class="bi bi-list fs-4"></i>
                </button>
                
                <!-- Breadcrumb -->
                <nav aria-label="breadcrumb">
                    <ol class="breadcrumb mb-0">
                        @hasSection('breadcrumb')
                            @yield('breadcrumb')
                        @else
                            <li class="breadcrumb-item active">Dashboard</li>
                        @endif
                    </ol>
                </nav>
            </div>
            
            <div class="d-flex align-items-center">
                <!-- Notifications -->
                <div class="dropdown me-3">
                    <button class="btn btn-link text-dark position-relative" type="button" 
                            id="notificationDropdown" data-bs-toggle="dropdown">
                        <i class="bi bi-bell fs-5"></i>
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            3
                        </span>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end" style="min-width: 300px;">
                        <li class="dropdown-header">Thông báo mới</li>
                        <li><hr class="dropdown-divider"></li>
                        <li class="dropdown-item">
                            <div class="d-flex">
                                <div class="flex-shrink-0">
                                    <div class="bg-success rounded-circle d-flex align-items-center justify-content-center" 
                                         style="width: 35px; height: 35px;">
                                        <i class="bi bi-cart-check text-white"></i>
                                    </div>
                                </div>
                                <div class="flex-grow-1 ms-3">
                                    <div class="fw-semibold">Đơn hàng mới</div>
                                    <small class="text-muted">2 phút trước</small>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
                
                <!-- User Profile -->
                <div class="dropdown">
                    <button class="btn btn-link text-dark d-flex align-items-center" type="button" 
                            id="userDropdown" data-bs-toggle="dropdown">
                        <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center me-2" 
                             style="width: 32px; height: 32px;">
                            <i class="bi bi-person text-white"></i>
                        </div>
                        <span class="fw-semibold">{{ auth()->user()->name ?? 'Admin' }}</span>
                        <i class="bi bi-chevron-down ms-1"></i>
                    </button>
                    <ul class="dropdown-menu dropdown-menu-end">
                        <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i>Hồ sơ</a></li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i>Cài đặt</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li>
                            <form action="{{ route('logout') }}" method="POST">
                                @csrf
                                <button type="submit" class="dropdown-item">
                                    <i class="bi bi-box-arrow-right me-2"></i>Đăng xuất
                                </button>
                            </form>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
