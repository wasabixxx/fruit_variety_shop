@extends('layouts.admin')

@section('title', 'Dashboard - Admin Panel')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1">Dashboard</h1>
        <p class="text-muted mb-0">Xin chào, {{ auth()->user()->name }}! Đây là tổng quan hệ thống.</p>
    </div>
    <div class="text-muted">
        <i class="bi bi-calendar3"></i>
        {{ now()->format('d/m/Y') }}
    </div>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-5">
    <div class="col-xl-3 col-md-6">
        <div class="admin-stats-card card">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 fw-semibold">Tổng đơn hàng</p>
                        <h2 class="fw-bold mb-0 text-dark">{{ number_format($totalOrders ?? 0) }}</h2>
                        <div class="d-flex align-items-center mt-2">
                            <span class="badge badge-admin-success rounded-pill">
                                <i class="bi bi-arrow-up me-1"></i>12%
                            </span>
                            <small class="text-muted ms-2">so với tháng trước</small>
                        </div>
                    </div>
                    <div class="bg-primary bg-opacity-10 rounded-circle p-3">
                        <i class="bi bi-cart3 fs-3 text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="admin-stats-card card success">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 fw-semibold">Doanh thu</p>
                        <h2 class="fw-bold mb-0 text-dark">{{ number_format(($totalRevenue ?? 0), 0, ',', '.') }}đ</h2>
                        <div class="d-flex align-items-center mt-2">
                            <span class="badge badge-admin-success rounded-pill">
                                <i class="bi bi-arrow-up me-1"></i>18%
                            </span>
                            <small class="text-muted ms-2">so với tháng trước</small>
                        </div>
                    </div>
                    <div class="bg-success bg-opacity-10 rounded-circle p-3">
                        <i class="bi bi-currency-dollar fs-3 text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="admin-stats-card card warning">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 fw-semibold">Sản phẩm</p>
                        <h2 class="fw-bold mb-0 text-dark">{{ number_format($totalProducts ?? 0) }}</h2>
                        <div class="d-flex align-items-center mt-2">
                            <span class="badge badge-admin-warning rounded-pill">
                                <i class="bi bi-plus me-1"></i>{{ $totalCategories ?? 0 }} danh mục
                            </span>
                        </div>
                    </div>
                    <div class="bg-warning bg-opacity-10 rounded-circle p-3">
                        <i class="bi bi-box fs-3 text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-xl-3 col-md-6">
        <div class="admin-stats-card card">
            <div class="card-body p-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <p class="text-muted mb-1 fw-semibold">Người dùng</p>
                        <h2 class="fw-bold mb-0 text-dark">{{ number_format($totalUsers ?? 0) }}</h2>
                        <div class="d-flex align-items-center mt-2">
                            <span class="badge badge-admin-primary rounded-pill">
                                <i class="bi bi-person-plus me-1"></i>5 mới
                            </span>
                            <small class="text-muted ms-2">tuần này</small>
                        </div>
                    </div>
                    <div class="bg-info bg-opacity-10 rounded-circle p-3">
                        <i class="bi bi-people fs-3 text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Quick Actions -->
<div class="row g-4 mb-5">
    <div class="col-md-8">
        <div class="admin-card card">
            <div class="admin-card-header d-flex justify-content-between align-items-center">
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
                        <a href="{{ route('admin.orders') }}" class="btn btn-outline-primary w-100 py-3">
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
                        <a href="{{ route('admin.users') }}" class="btn btn-outline-info w-100 py-3">
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
                <div class="d-flex align-items-center">
                    <div class="bg-success bg-opacity-10 rounded p-2 me-3">
                        <i class="bi bi-graph-up text-success"></i>
                    </div>
                    <h5 class="fw-bold mb-0">Hoạt động hôm nay</h5>
                </div>
            </div>
            <div class="admin-card-body">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                            <i class="bi bi-cart-check text-primary"></i>
                        </div>
                        <div>
                            <div class="fw-semibold">Đơn hàng mới</div>
                            <small class="text-muted">2 giờ trước</small>
                        </div>
                    </div>
                    <span class="badge badge-admin-primary">3</span>
                </div>
                
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center">
                        <div class="bg-success bg-opacity-10 rounded-circle p-2 me-3">
                            <i class="bi bi-person-plus text-success"></i>
                        </div>
                        <div>
                            <div class="fw-semibold">Người dùng mới</div>
                            <small class="text-muted">1 giờ trước</small>
                        </div>
                    </div>
                    <span class="badge badge-admin-success">5</span>
                </div>
                
                <div class="d-flex align-items-center justify-content-between">
                    <div class="d-flex align-items-center">
                        <div class="bg-warning bg-opacity-10 rounded-circle p-2 me-3">
                            <i class="bi bi-box text-warning"></i>
                        </div>
                        <div>
                            <div class="fw-semibold">Sản phẩm thêm</div>
                            <small class="text-muted">30 phút trước</small>
                        </div>
                    </div>
                    <span class="badge badge-admin-warning">2</span>
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
                        <div class="bg-primary bg-opacity-10 rounded p-2 me-3">
                            <i class="bi bi-clock-history text-primary"></i>
                        </div>
                        <h5 class="fw-bold mb-0">Sản phẩm mới nhất</h5>
                    </div>
                    <a href="{{ route('products.index') }}" class="btn btn-sm btn-outline-primary">
                        Xem tất cả <i class="bi bi-arrow-right ms-1"></i>
                    </a>
                </div>
            </div>
            <div class="admin-card-body p-0">
                <div class="table-responsive">
                    <table class="table admin-table mb-0">
                        <thead>
                            <tr>
                                <th class="border-0">Sản phẩm</th>
                                <th class="border-0">Danh mục</th>
                                <th class="border-0">Giá</th>
                                <th class="border-0">Tồn kho</th>
                                <th class="border-0">Ngày tạo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentProducts ?? [] as $product)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($product->image)
                                            <img src="{{ $product->image }}" 
                                                 class="rounded me-3" 
                                                 style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center"
                                                 style="width: 40px; height: 40px;">
                                                <i class="bi bi-image text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-semibold">{{ $product->name }}</div>
                                            <small class="text-muted">#{{ $product->id }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge badge-admin-primary rounded-pill">
                                        {{ $product->category->name ?? 'N/A' }}
                                    </span>
                                </td>
                                <td class="fw-semibold">{{ number_format($product->price, 0, ',', '.') }}đ</td>
                                <td>
                                    @if($product->stock > 10)
                                        <span class="badge badge-admin-success rounded-pill">{{ $product->stock }}</span>
                                    @elseif($product->stock > 0)
                                        <span class="badge badge-admin-warning rounded-pill">{{ $product->stock }}</span>
                                    @else
                                        <span class="badge badge-admin-danger rounded-pill">{{ $product->stock }}</span>
                                    @endif
                                </td>
                                <td class="text-muted">{{ $product->created_at->format('d/m/Y') }}</td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="text-center py-4 text-muted">
                                    <i class="bi bi-inbox fs-1 d-block mb-2 opacity-50"></i>
                                    Chưa có sản phẩm nào
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
