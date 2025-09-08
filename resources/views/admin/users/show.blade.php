@extends('admin.layout')

@section('title', 'Chi tiết người dùng - ' . $user->name . ' - Admin Panel')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Quản lý User</a></li>
    <li class="breadcrumb-item active">{{ $user->name }}</li>
@endsection

@section('content')
<!-- Page Title -->
<div class="page-title">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1>Chi tiết người dùng</h1>
            <p class="page-subtitle">Xem thông tin chi tiết của người dùng</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left me-2"></i>Quay lại danh sách
            </a>
            <a href="{{ route('admin.users.orders', $user) }}" class="btn btn-info">
                <i class="bi bi-bag me-2"></i>Xem đơn hàng
            </a>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- User Information -->
    <div class="col-lg-8">
        <div class="admin-card card">
            <div class="admin-card-header">
                <h5 class="mb-0">
                    <i class="bi bi-person me-2"></i>
                    Thông tin cá nhân
                </h5>
            </div>
            <div class="admin-card-body">
                <div class="row g-4">
                    <div class="col-12">
                        <div class="d-flex align-items-center mb-4">
                            <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-4"
                                 style="width: 80px; height: 80px;">
                                <i class="bi bi-person text-primary" style="font-size: 2rem;"></i>
                            </div>
                            <div>
                                <h3 class="mb-1">{{ $user->name }}</h3>
                                <p class="text-muted mb-0">{{ $user->email }}</p>
                                <small class="text-muted">
                                    Vai trò: 
                                    @if($user->role == 'admin')
                                        <span class="badge badge-danger">Quản trị viên</span>
                                    @else
                                        <span class="badge badge-primary">Người dùng</span>
                                    @endif
                                </small>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <label class="form-label text-muted">ID</label>
                        <div class="fw-bold text-primary fs-4">#{{ $user->id }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Email</label>
                        <div class="fw-semibold">{{ $user->email }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Họ và tên</label>
                        <div class="fw-semibold">{{ $user->name }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Vai trò</label>
                        <div>
                            @if($user->role == 'admin')
                                <span class="badge badge-danger">Quản trị viên</span>
                            @else
                                <span class="badge badge-primary">Người dùng</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Trạng thái email</label>
                        <div>
                            @if($user->email_verified_at)
                                <span class="badge badge-success">
                                    <i class="bi bi-check-circle me-1"></i>Đã xác thực
                                </span>
                            @else
                                <span class="badge badge-warning">
                                    <i class="bi bi-clock me-1"></i>Chưa xác thực
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Ngày xác thực email</label>
                        <div class="fw-semibold">
                            {{ $user->email_verified_at ? $user->email_verified_at->format('d/m/Y H:i') : 'Chưa xác thực' }}
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Ngày đăng ký</label>
                        <div class="fw-semibold">{{ $user->created_at->format('d/m/Y H:i') }}</div>
                        <small class="text-muted">{{ $user->created_at->diffForHumans() }}</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Cập nhật cuối</label>
                        <div class="fw-semibold">{{ $user->updated_at->format('d/m/Y H:i') }}</div>
                        <small class="text-muted">{{ $user->updated_at->diffForHumans() }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Quick Actions -->
        <div class="admin-card card mb-4">
            <div class="admin-card-header">
                <h5 class="mb-0">
                    <i class="bi bi-gear me-2"></i>
                    Thao tác nhanh
                </h5>
            </div>
            <div class="admin-card-body d-flex flex-column gap-3">
                <a href="{{ route('admin.users.orders', $user) }}" class="btn btn-info w-100">
                    <i class="bi bi-bag me-2"></i>Xem đơn hàng của user
                </a>
                <a href="mailto:{{ $user->email }}" class="btn btn-outline-primary w-100">
                    <i class="bi bi-envelope me-2"></i>Gửi email
                </a>
            </div>
        </div>

        <!-- User Statistics -->
        <div class="admin-card card mb-4">
            <div class="admin-card-header">
                <h5 class="mb-0">
                    <i class="bi bi-bar-chart me-2"></i>
                    Thống kê
                </h5>
            </div>
            <div class="admin-card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Tổng đơn hàng</span>
                    <span class="badge badge-primary">{{ $user->orders->count() }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Đơn hoàn thành</span>
                    <span class="badge badge-success">{{ $user->orders->where('order_status', 'delivered')->count() }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Đơn đang xử lý</span>
                    <span class="badge badge-warning">{{ $user->orders->whereIn('order_status', ['pending', 'confirmed', 'processing'])->count() }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span>Tổng chi tiêu</span>
                    <span class="fw-bold text-success">{{ number_format($user->orders->where('order_status', 'delivered')->sum('total_amount'), 0, ',', '.') }}đ</span>
                </div>
            </div>
        </div>

        <!-- Account Status -->
        <div class="admin-card card">
            <div class="admin-card-header">
                <h5 class="mb-0">
                    <i class="bi bi-shield-check me-2"></i>
                    Trạng thái tài khoản
                </h5>
            </div>
            <div class="admin-card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Email xác thực</span>
                    @if($user->email_verified_at)
                        <span class="badge badge-success">Đã xác thực</span>
                    @else
                        <span class="badge badge-warning">Chưa xác thực</span>
                    @endif
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Vai trò</span>
                    @if($user->role == 'admin')
                        <span class="badge badge-danger">Admin</span>
                    @else
                        <span class="badge badge-primary">User</span>
                    @endif
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span>Thành viên từ</span>
                    <small class="text-muted">{{ $user->created_at->format('m/Y') }}</small>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Orders -->
@if($user->orders->count() > 0)
<div class="admin-card card mt-4">
    <div class="admin-card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-bag me-2"></i>
                Đơn hàng gần đây
            </h5>
            <a href="{{ route('admin.users.orders', $user) }}" class="btn btn-sm btn-outline-primary">
                Xem tất cả
            </a>
        </div>
    </div>
    <div class="admin-card-body p-0">
        <div class="table-responsive">
            <table class="table admin-table mb-0">
                <thead>
                    <tr>
                        <th>Mã đơn</th>
                        <th>Ngày đặt</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($user->orders->take(5) as $order)
                    <tr>
                        <td><span class="fw-bold text-primary">#{{ $order->id }}</span></td>
                        <td>{{ $order->created_at->format('d/m/Y') }}</td>
                        <td><span class="fw-bold text-success">{{ number_format($order->total_amount, 0, ',', '.') }}đ</span></td>
                        <td>
                            @php
                                $statusConfig = [
                                    'pending' => ['class' => 'warning', 'text' => 'Chờ xử lý'],
                                    'confirmed' => ['class' => 'info', 'text' => 'Đã xác nhận'],
                                    'processing' => ['class' => 'primary', 'text' => 'Đang xử lý'],
                                    'shipped' => ['class' => 'secondary', 'text' => 'Đã giao'],
                                    'delivered' => ['class' => 'success', 'text' => 'Hoàn thành'],
                                    'cancelled' => ['class' => 'danger', 'text' => 'Đã hủy']
                                ];
                                $config = $statusConfig[$order->order_status] ?? $statusConfig['pending'];
                            @endphp
                            <span class="badge badge-{{ $config['class'] }}">{{ $config['text'] }}</span>
                        </td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline-info btn-sm">
                                <i class="bi bi-eye"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@else
<div class="admin-card card mt-4">
    <div class="admin-card-body text-center py-5">
        <div class="empty-state">
            <i class="bi bi-bag"></i>
            <h5>Chưa có đơn hàng nào</h5>
            <p>Người dùng này chưa đặt đơn hàng nào</p>
        </div>
    </div>
</div>
@endif
@endsection
