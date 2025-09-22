@extends('admin.layout')

@section('title', 'Quản lý User - Admin Panel')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Quản lý User</li>
@endsection

@section('content')
<!-- Page Title -->
<div class="page-title">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1>Quản lý User</h1>
            <p class="page-subtitle">Xem và quản lý tất cả người dùng hệ thống</p>
        </div>
    </div>
</div>

<!-- Users Table -->
<div class="admin-card card">
    <div class="admin-card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-people me-2"></i>
                Danh sách User ({{ $users->total() }})
            </h5>
            <div class="d-flex align-items-center">
                <input type="text" class="form-control search-filter me-3" placeholder="Tìm kiếm..." style="width: 250px;">
                <span class="badge badge-primary">{{ $users->total() }} user</span>
            </div>
        </div>
    </div>
    <div class="admin-card-body p-0">
        <div class="table-responsive">
            <table class="table admin-table mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Avatar</th>
                        <th>Tên</th>
                        <th>Email</th>
                        <th>Vai trò</th>
                        <th>Trạng thái Email</th>
                        <th>Số đơn hàng</th>
                        <th>Ngày đăng ký</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td>
                            <span class="fw-bold text-primary">#{{ $user->id }}</span>
                        </td>
                        <td>
                            <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                                 style="width: 40px; height: 40px;">
                                <i class="bi bi-person text-primary"></i>
                            </div>
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $user->name }}</div>
                            <small class="text-muted">ID: #{{ $user->id }}</small>
                        </td>
                        <td>
                            <span class="text-muted">{{ $user->email }}</span>
                        </td>
                        <td>
                            @if($user->role === 'admin')
                                <span class="badge badge-danger">
                                    <i class="bi bi-shield-check me-1"></i>Admin
                                </span>
                            @else
                                <span class="badge badge-primary">
                                    <i class="bi bi-person me-1"></i>User
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($user->hasVerifiedEmail())
                                <span class="badge badge-success">
                                    <i class="bi bi-check-circle me-1"></i>Đã xác thực
                                </span>
                                <br>
                                <small class="text-muted">{{ $user->email_verified_at->format('d/m/Y H:i') }}</small>
                            @else
                                <span class="badge badge-warning">
                                    <i class="bi bi-exclamation-triangle me-1"></i>Chưa xác thực
                                </span>
                            @endif
                        </td>
                        <td>
                            @if($user->orders_count > 0)
                                <span class="badge badge-success">{{ $user->orders_count }} đơn</span>
                            @else
                                <span class="text-muted">Chưa có đơn hàng</span>
                            @endif
                        </td>
                        <td>
                            <span class="text-muted">{{ $user->created_at->format('d/m/Y') }}</span>
                            <br>
                            <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                        </td>
                        <td>
                            @if($user->orders_count > 0)
                                <a href="{{ route('admin.users.orders', $user) }}" class="btn btn-outline-info btn-sm">
                                    <i class="bi bi-eye me-1"></i>Xem đơn hàng
                                </a>
                            @else
                                <span class="text-muted">Chưa có hoạt động</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <div class="empty-state">
                                <i class="bi bi-people"></i>
                                <h5>Chưa có user nào</h5>
                                <p>Hệ thống chưa có user đăng ký</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($users->hasPages())
    <div class="admin-card-body border-top">
        <div class="d-flex justify-content-center">
            {{ $users->links() }}
        </div>
    </div>
    @endif
</div>

<!-- User Stats -->
<div class="row g-4 mt-4">
    <div class="col-md-3">
        <div class="card stats-card primary">
            <div class="card-body text-center">
                <i class="bi bi-people fs-1 mb-2"></i>
                <h4>{{ $users->total() }}</h4>
                <p class="mb-0 opacity-75">Tổng user</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card success">
            <div class="card-body text-center">
                <i class="bi bi-person-check fs-1 mb-2"></i>
                <h4>{{ $users->where('role', 'user')->count() }}</h4>
                <p class="mb-0 opacity-75">Khách hàng</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card info">
            <div class="card-body text-center">
                <i class="bi bi-envelope-check fs-1 mb-2"></i>
                <h4>{{ $verifiedUsers }}</h4>
                <p class="mb-0 opacity-75">Đã xác thực email</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card warning">
            <div class="card-body text-center">
                <i class="bi bi-envelope-exclamation fs-1 mb-2"></i>
                <h4>{{ $unverifiedUsers }}</h4>
                <p class="mb-0 opacity-75">Chưa xác thực email</p>
            </div>
        </div>
    </div>
</div>

<!-- Additional Stats Row -->
<div class="row g-4 mt-2">
    <div class="col-md-4">
        <div class="card stats-card danger">
            <div class="card-body text-center">
                <i class="bi bi-shield-check fs-1 mb-2"></i>
                <h4>{{ $users->where('role', 'admin')->count() }}</h4>
                <p class="mb-0 opacity-75">Quản trị viên</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stats-card primary">
            <div class="card-body text-center">
                <i class="bi bi-cart-check fs-1 mb-2"></i>
                <h4>{{ $users->sum('orders_count') }}</h4>
                <p class="mb-0 opacity-75">Tổng đơn hàng</p>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stats-card secondary">
            <div class="card-body text-center">
                <i class="bi bi-calendar-event fs-1 mb-2"></i>
                <h4>{{ $users->where('created_at', '>=', now()->subDays(30))->count() }}</h4>
                <p class="mb-0 opacity-75">Đăng ký tháng này</p>
            </div>
        </div>
    </div>
</div>
@endsection
