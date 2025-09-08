@extends('admin.layout')

@section('title', 'Đơn hàng của ' . $user->name . ' - Admin Panel')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.users.index') }}">Quản lý Người dùng</a></li>
    <li class="breadcrumb-item active">Đơn hàng của {{ $user->name }}</li>
@endsection

@section('content')
<!-- Page Title -->
<div class="page-title">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1>Đơn hàng của {{ $user->name }}</h1>
            <p class="page-subtitle">Email: {{ $user->email }} | Đăng ký: {{ $user->created_at->format('d/m/Y') }}</p>
        </div>
        <a href="{{ route('admin.users.index') }}" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left me-2"></i>Quay lại danh sách
        </a>
    </div>
</div>

@if($orders->count() > 0)
    <div class="admin-card card">
        <div class="admin-card-header">
            <h5 class="mb-0">
                <i class="bi bi-receipt me-2"></i>
                Danh sách đơn hàng ({{ $orders->total() }})
            </h5>
        </div>
        <div class="admin-card-body">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Mã đơn hàng</th>
                            <th>Ngày đặt</th>
                            <th>Tổng tiền</th>
                            <th>Phương thức</th>
                            <th>Trạng thái đơn</th>
                            <th>Thanh toán</th>
                            <th>Thao tác</th>
                        </tr>
                    </thead>
                        <tbody>
                            @foreach($orders as $order)
                            <tr>
                                <td><strong>#{{ $order->id }}</strong></td>
                                <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                <td class="fw-bold text-success">{{ number_format($order->total_amount) }} VNĐ</td>
                                <td>
                                    @if($order->payment_method == 'cod')
                                        <span class="badge bg-warning">COD</span>
                                    @elseif($order->payment_method == 'momo_atm') 
                                        <span class="badge bg-primary">MoMo ATM</span>
                                    @elseif($order->payment_method == 'momo_card')
                                        <span class="badge bg-info">MoMo Card</span>
                                    @elseif($order->payment_method == 'momo_wallet')
                                        <span class="badge bg-secondary">MoMo Wallet</span>
                                    @endif
                                </td>
                                <td>
                                    @php
                                        $statusColors = [
                                            'pending' => 'warning',
                                            'confirmed' => 'info', 
                                            'processing' => 'primary',
                                            'shipped' => 'secondary',
                                            'delivered' => 'success',
                                            'cancelled' => 'danger'
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $statusColors[$order->order_status] ?? 'secondary' }}">
                                        {{ $order->status_label }}
                                    </span>
                                </td>
                                <td>
                                    @php
                                        $paymentColors = [
                                            'pending' => 'warning',
                                            'paid' => 'success',
                                            'failed' => 'danger',
                                            'refunded' => 'info'
                                        ];
                                    @endphp
                                    <span class="badge bg-{{ $paymentColors[$order->payment_status] ?? 'secondary' }}">
                                        {{ $order->payment_status_label }}
                                    </span>
                                </td>
                                <td>
                                    <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-primary btn-sm">
                                        <i class="bi bi-eye me-1"></i>Chi tiết
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            @if($orders->hasPages())
            <div class="admin-card-footer">
                {{ $orders->links() }}
            </div>
            @endif
        </div>

        <!-- Order Summary -->
        <div class="row mt-4">
            <div class="col-md-3">
                <div class="admin-card card">
                    <div class="admin-card-body text-center">
                        <h4 class="text-primary">{{ $orders->total() }}</h4>
                        <p class="card-text">Tổng đơn hàng</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="admin-card card">
                    <div class="admin-card-body text-center">
                        <h4 class="text-success">{{ number_format($orders->sum('total_amount')) }} VNĐ</h4>
                        <p class="card-text">Tổng giá trị</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="admin-card card">
                    <div class="admin-card-body text-center">
                        <h4 class="text-warning">{{ $orders->where('order_status', 'pending')->count() }}</h4>
                        <p class="card-text">Đang chờ</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="admin-card card">
                    <div class="admin-card-body text-center">
                        <h4 class="text-info">{{ $orders->where('order_status', 'delivered')->count() }}</h4>
                        <p class="card-text">Đã giao</p>
                    </div>
                </div>
            </div>
        </div>
    @else
        <div class="admin-card card">
            <div class="admin-card-body text-center py-5">
                <i class="bi bi-cart-x display-1 text-muted mb-3"></i>
                <h3 class="text-muted">Người dùng này chưa có đơn hàng nào</h3>
                <p class="text-muted">Chưa có giao dịch nào được thực hiện.</p>
            </div>
        </div>
    @endif
@endsection
