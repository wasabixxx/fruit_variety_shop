@extends('layouts.admin')

@section('title', 'Quản lý người dùng - Admin Panel')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Quản lý người dùng</li>
@endsection

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1">Quản lý người dùng</h1>
        <p class="text-muted mb-0">Xem và quản lý tất cả người dùng hệ thống</p>
    </div>
</div>

<!-- Users Table -->
<div class="admin-card card">
    <div class="admin-card-header">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <div class="bg-primary bg-opacity-10 rounded p-2 me-3">
                    <i class="bi bi-people text-primary"></i>
                </div>
                <h5 class="fw-bold mb-0">Danh sách người dùng ({{ $users->total() }})</h5>
            </div>
        </div>
    </div>
    <div class="admin-card-body p-0">
        <div class="table-responsive">
            <table class="admin-table table mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên</th>
                        <th>Email</th>
                        <th>Vai trò</th>
                        <th>Số đơn hàng</th>
                        <th>Ngày đăng ký</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td><strong>#{{ $user->id }}</strong></td>
                        <td class="fw-semibold">{{ $user->name }}</td>
                        <td class="text-muted">{{ $user->email }}</td>
                        <td>
                            @if($user->role === 'admin')
                                <span class="badge badge-admin-danger rounded-pill">Admin</span>
                            @else
                                <span class="badge badge-admin-primary rounded-pill">User</span>
                            @endif
                        </td>
                        <td>
                            @if($user->orders_count > 0)
                                <span class="badge badge-admin-success rounded-pill">{{ $user->orders_count }}</span>
                            @else
                                <span class="text-muted">0</span>
                            @endif
                        </td>
                        <td class="text-muted">{{ $user->created_at->format('d/m/Y') }}</td>
                        <td>
                            @if($user->orders_count > 0)
                                <a href="{{ route('admin.users.orders', $user) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-eye me-1"></i>Xem đơn hàng
                                </a>
                            @else
                                <span class="text-muted">Chưa có đơn hàng</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <i class="bi bi-people fs-1 d-block mb-3 text-muted opacity-50"></i>
                            <p class="text-muted mb-0">Không có người dùng nào</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($users->hasPages())
    <div class="admin-card-footer">
        {{ $users->links() }}
    </div>
    @endif
</div>
@endsection
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($users->hasPages())
        <div class="card-footer">
            {{ $users->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
