@extends('admin.layout')

@section('title', 'Dashboard - Admin Panel')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<!-- Page Title -->
<div class="page-title">
    <h1>Dashboard</h1>
    <p class="page-subtitle">Tổng quan hệ thống quản lý</p>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-5">
    <div class="col-md-3">
        <div class="card stats-card primary">
            <div class="card-body text-center">
                <i class="bi bi-tags fs-1 mb-2"></i>
                <h3>{{ $totalCategories }}</h3>
                <p class="mb-0 opacity-75">Danh mục</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card success">
            <div class="card-body text-center">
                <i class="bi bi-box fs-1 mb-2"></i>
                <h3>{{ $totalProducts }}</h3>
                <p class="mb-0 opacity-75">Sản phẩm</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card warning">
            <div class="card-body text-center">
                <i class="bi bi-people fs-1 mb-2"></i>
                <h3>{{ $totalUsers }}</h3>
                <p class="mb-0 opacity-75">Người dùng</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card info">
            <div class="card-body text-center">
                <i class="bi bi-bag-check fs-1 mb-2"></i>
                <h3>{{ $totalOrders }}</h3>
                <p class="mb-0 opacity-75">Đơn hàng</p>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row g-4 mb-5">
    <div class="col-md-8">
        <div class="admin-card card">
            <div class="admin-card-header">
                <div class="d-flex align-items-center">
                    <div class="bg-primary bg-opacity-10 rounded p-2 me-3">
                        <i class="bi bi-lightning text-primary"></i>
                    </div>
                    <h5 class="fw-bold mb-0">Thao tác nhanh</h5>
                </div>
            </div>
            <div class="admin-card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-primary w-100 py-3">
                            <i class="bi bi-cart-plus fs-4 d-block mb-2"></i>
                            <div class="fw-semibold">Quản lý đơn hàng</div>
                            <small class="text-muted">Xem và xử lý đơn hàng</small>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('admin.products.create') }}" class="btn btn-outline-success w-100 py-3">
                            <i class="bi bi-plus-circle fs-4 d-block mb-2"></i>
                            <div class="fw-semibold">Thêm sản phẩm</div>
                            <small class="text-muted">Tạo sản phẩm mới</small>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-info w-100 py-3">
                            <i class="bi bi-person-gear fs-4 d-block mb-2"></i>
                            <div class="fw-semibold">Quản lý người dùng</div>
                            <small class="text-muted">Xem danh sách người dùng</small>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="{{ route('admin.categories.create') }}" class="btn btn-outline-warning w-100 py-3">
                            <i class="bi bi-tags fs-4 d-block mb-2"></i>
                            <div class="fw-semibold">Thêm danh mục</div>
                            <small class="text-muted">Tạo danh mục mới</small>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="admin-card card">
            <div class="admin-card-header">
                <h5 class="fw-bold mb-0">
                    <i class="bi bi-clock text-primary me-2"></i>
                    <span id="admin-clock">--:--:--</span>
                </h5>
            </div>
            <div class="admin-card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="text-muted">Hôm nay</span>
                    <span class="fw-semibold">{{ now()->format('d/m/Y') }}</span>
                </div>
                <div class="text-center">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-2" 
                         style="width: 60px; height: 60px;">
                        <i class="bi bi-calendar-check text-primary fs-3"></i>
                    </div>
                    <div class="fw-semibold">Chào mừng trở lại!</div>
                    <small class="text-muted">{{ auth()->user()->name ?? 'Admin' }}</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Data -->
<div class="row g-4">
    <div class="col-md-8">
        <div class="admin-card card">
            <div class="admin-card-header">
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 rounded p-2 me-3">
                            <i class="bi bi-cart-check text-success"></i>
                        </div>
                        <h5 class="fw-bold mb-0">Đơn hàng gần đây</h5>
                    </div>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">
                        Xem tất cả <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
            <div class="admin-card-body p-0">
                <div class="table-responsive">
                    <table class="table admin-table mb-0">
                        <thead>
                            <tr>
                                <th>Mã đơn</th>
                                <th>Khách hàng</th>
                                <th>Trạng thái</th>
                                <th>Tổng tiền</th>
                                <th>Ngày đặt</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders ?? [] as $order)
                            <tr>
                                <td>
                                    <span class="fw-bold text-primary">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
                                </td>
                                <td>{{ $order->customer_name }}</td>
                                <td>
                                    @switch($order->order_status)
                                        @case('pending')
                                            <span class="badge badge-warning">Chờ xử lý</span>
                                            @break
                                        @case('confirmed')
                                            <span class="badge badge-info">Đã xác nhận</span>
                                            @break
                                        @case('delivered')
                                            <span class="badge badge-success">Hoàn thành</span>
                                            @break
                                        @default
                                            <span class="badge badge-secondary">{{ $order->order_status }}</span>
                                    @endswitch
                                </td>
                                <td>
                                    <span class="fw-bold text-success">{{ number_format($order->total_amount, 0, ',', '.') }}đ</span>
                                </td>
                                <td>{{ $order->created_at->format('d/m/Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    Chưa có đơn hàng nào
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="admin-card card">
            <div class="admin-card-header">
                <div class="d-flex align-items-center">
                    <div class="bg-info bg-opacity-10 rounded p-2 me-3">
                        <i class="bi bi-pie-chart text-info"></i>
                    </div>
                    <h5 class="fw-bold mb-0">Thống kê nhanh</h5>
                </div>
            </div>
            <div class="admin-card-body">
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Đơn hàng hoàn thành</span>
                        <span class="fw-semibold">85%</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-success" style="width: 85%"></div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Sản phẩm còn hàng</span>
                        <span class="fw-semibold">92%</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-primary" style="width: 92%"></div>
                    </div>
                </div>
                
                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="text-muted">Độ hài lòng khách hàng</span>
                        <span class="fw-semibold">96%</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-warning" style="width: 96%"></div>
                    </div>
                </div>
                
                <div class="text-center pt-3">
                    <a href="#" class="btn btn-sm btn-outline-info">
                        Xem báo cáo chi tiết
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
