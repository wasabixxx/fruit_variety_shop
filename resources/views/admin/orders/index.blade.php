@extends('layouts.admin')

@section('title', 'Quản lý đơn hàng - Admin Panel')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Quản lý đơn hàng</li>
@endsection

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1">Quản lý đơn hàng</h1>
        <p class="text-muted mb-0">Xem và quản lý tất cả đơn hàng của khách hàng</p>
    </div>
</div>

<!-- Filter Card -->
<div class="admin-card card mb-4">
    <div class="admin-card-header">
        <div class="d-flex align-items-center">
            <div class="bg-primary bg-opacity-10 rounded p-2 me-3">
                <i class="bi bi-funnel text-primary"></i>
            </div>
            <h5 class="fw-bold mb-0">Bộ lọc</h5>
        </div>
    </div>
    <div class="admin-card-body">
        <form method="GET" class="row g-3">
            <div class="col-md-3">
                <label class="form-label fw-semibold">Trạng thái đơn hàng</label>
                <select name="status" class="form-control-admin">
                    <option value="">Tất cả</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                    <option value="processing" {{ request('status') == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                    <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Đang giao hàng</option>
                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Đã giao hàng</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label fw-semibold">Phương thức thanh toán</label>
                <select name="payment_method" class="form-control-admin">
                    <option value="">Tất cả</option>
                    <option value="cod" {{ request('payment_method') == 'cod' ? 'selected' : '' }}>COD</option>
                    <option value="momo_atm" {{ request('payment_method') == 'momo_atm' ? 'selected' : '' }}>MoMo ATM</option>
                    <option value="momo_card" {{ request('payment_method') == 'momo_card' ? 'selected' : '' }}>MoMo Card</option>
                    <option value="momo_wallet" {{ request('payment_method') == 'momo_wallet' ? 'selected' : '' }}>MoMo Wallet</option>
                </select>
            </div>
            <div class="col-md-4">
                <label class="form-label fw-semibold">Tìm kiếm</label>
                <input type="text" name="search" class="form-control-admin" placeholder="Tên khách hàng hoặc số điện thoại..." value="{{ request('search') }}">
            </div>
            <div class="col-md-2">
                <label class="form-label">&nbsp;</label>
                <button type="submit" class="btn btn-admin-primary d-block w-100">
                    <i class="bi bi-search me-2"></i>Lọc
                </button>
            </div>
        </form>
    </div>
</div>
<!-- Orders Table -->
<div class="admin-card card">
    <div class="admin-card-header">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <div class="bg-primary bg-opacity-10 rounded p-2 me-3">
                    <i class="bi bi-cart-check text-primary"></i>
                </div>
                <h5 class="fw-bold mb-0">Danh sách đơn hàng ({{ $orders->total() }})</h5>
            </div>
        </div>
    </div>
    <div class="admin-card-body p-0">
        <div class="table-responsive">
            <table class="admin-table table mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Khách hàng</th>
                        <th>Tổng tiền</th>
                        <th>Phương thức</th>
                        <th>Trạng thái đơn</th>
                        <th>Thanh toán</th>
                        <th>Ngày đặt</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td><strong>#{{ $order->id }}</strong></td>
                        <td>
                            <div class="fw-semibold">{{ $order->customer_name }}</div>
                            <small class="text-muted">{{ $order->customer_phone }}</small>
                        </td>
                        <td class="fw-bold text-success">{{ number_format($order->total_amount) }}đ</td>
                        <td>
                            @if($order->payment_method == 'cod')
                                <span class="badge badge-admin-warning rounded-pill">COD</span>
                            @elseif($order->payment_method == 'momo_atm') 
                                <span class="badge badge-admin-primary rounded-pill">MoMo ATM</span>
                            @elseif($order->payment_method == 'momo_card')
                                <span class="badge badge-admin-info rounded-pill">MoMo Card</span>
                            @elseif($order->payment_method == 'momo_wallet')
                                <span class="badge badge-admin-secondary rounded-pill">MoMo Wallet</span>
                            @endif
                        </td>
                        <td>
                            @php
                                $statusClasses = [
                                    'pending' => 'badge-admin-warning',
                                    'confirmed' => 'badge-admin-info', 
                                    'processing' => 'badge-admin-primary',
                                    'shipped' => 'badge-admin-secondary',
                                    'delivered' => 'badge-admin-success',
                                    'cancelled' => 'badge-admin-danger'
                                ];
                            @endphp
                            <span class="badge {{ $statusClasses[$order->order_status] ?? 'badge-admin-secondary' }} rounded-pill">
                                {{ $order->status_label }}
                            </span>
                        </td>
                        <td>
                            @php
                                $paymentClasses = [
                                    'pending' => 'badge-admin-warning',
                                    'paid' => 'badge-admin-success',
                                    'failed' => 'badge-admin-danger',
                                    'refunded' => 'badge-admin-info'
                                ];
                            @endphp
                            <span class="badge {{ $paymentClasses[$order->payment_status] ?? 'badge-admin-secondary' }} rounded-pill">
                                {{ $order->payment_status_label }}
                            </span>
                        </td>
                        <td class="text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <div class="btn-group btn-group-sm">
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline-primary btn-sm">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <button type="button" class="btn btn-outline-danger btn-sm" 
                                        onclick="confirmDelete({{ $order->id }})">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <i class="bi bi-inbox fs-1 d-block mb-3 text-muted opacity-50"></i>
                            <p class="text-muted mb-0">Không có đơn hàng nào</p>
                        </td>
                    </tr>
                    @endforelse
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
@endsection

@section('scripts')
<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xác nhận xóa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                Bạn có chắc chắn muốn xóa đơn hàng này?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <form id="deleteForm" method="POST" style="display: inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger">Xóa</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function confirmDelete(orderId) {
    document.getElementById('deleteForm').action = `/admin/orders/${orderId}`;
    new bootstrap.Modal(document.getElementById('deleteModal')).show();
}
</script>
@endsection
