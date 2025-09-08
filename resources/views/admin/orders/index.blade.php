@extends('admin.layout')

@section('title', 'Quản lý Đơn hàng - Admin Panel')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Quản lý Đơn hàng</li>
@endsection

@section('content')
<!-- Page Title -->
<div class="page-title">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1>Quản lý Đơn hàng</h1>
            <p class="page-subtitle">Theo dõi và xử lý tất cả đơn hàng của khách hàng</p>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="admin-card card mb-4">
    <div class="admin-card-body">
        <form method="GET" action="{{ route('admin.orders.index') }}" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Tìm kiếm</label>
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Mã đơn hàng, tên khách hàng..." 
                           value="{{ request('search') }}">
                    <button class="btn btn-outline-primary" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label">Trạng thái</label>
                <select name="status" class="form-select">
                    <option value="">Tất cả trạng thái</option>
                    <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Chờ xác nhận</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                    <option value="shipped" {{ request('status') == 'shipped' ? 'selected' : '' }}>Đang giao</option>
                    <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Đã giao</option>
                    <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Đã huỷ</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Khoảng thời gian</label>
                <select name="date_range" class="form-select">
                    <option value="">Tất cả</option>
                    <option value="today" {{ request('date_range') == 'today' ? 'selected' : '' }}>Hôm nay</option>
                    <option value="yesterday" {{ request('date_range') == 'yesterday' ? 'selected' : '' }}>Hôm qua</option>
                    <option value="this_week" {{ request('date_range') == 'this_week' ? 'selected' : '' }}>Tuần này</option>
                    <option value="this_month" {{ request('date_range') == 'this_month' ? 'selected' : '' }}>Tháng này</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-funnel me-1"></i>Lọc
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Orders Table -->
<div class="admin-card card">
    <div class="admin-card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-bag-check me-2"></i>
                Danh sách Đơn hàng ({{ $orders->total() }})
            </h5>
            <div class="d-flex gap-2">
                <span class="badge badge-warning">{{ $orders->where('order_status', 'pending')->count() }} chờ xác nhận</span>
                <span class="badge badge-info">{{ $orders->where('order_status', 'confirmed')->count() }} đã xác nhận</span>
                <span class="badge badge-success">{{ $orders->where('order_status', 'delivered')->count() }} hoàn thành</span>
            </div>
        </div>
    </div>
    <div class="admin-card-body p-0">
        <div class="table-responsive">
            <table class="table admin-table mb-0">
                <thead>
                    <tr>
                        <th>Mã đơn hàng</th>
                        <th>Khách hàng</th>
                        <th>Sản phẩm</th>
                        <th>Tổng tiền</th>
                        <th>Trạng thái</th>
                        <th>Ngày đặt</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($orders as $order)
                    <tr>
                        <td>
                            <span class="fw-bold text-primary">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
                        </td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2"
                                     style="width: 35px; height: 35px;">
                                    <i class="bi bi-person text-primary"></i>
                                </div>
                                <div>
                                    <div class="fw-semibold">{{ $order->customer_name }}</div>
                                    <small class="text-muted">{{ $order->customer_phone }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $order->orderItems->count() }} sản phẩm</div>
                            <small class="text-muted">
                                {{ $order->orderItems->first()->product->name ?? 'N/A' }}
                                @if($order->orderItems->count() > 1)
                                    và {{ $order->orderItems->count() - 1 }} sản phẩm khác
                                @endif
                            </small>
                        </td>
                        <td>
                            <span class="fw-bold text-success">{{ number_format($order->total_amount, 0, ',', '.') }}đ</span>
                        </td>
                        <td>
                            @switch($order->order_status)
                                @case('pending')
                                    <span class="badge badge-warning">
                                        <i class="bi bi-clock me-1"></i>Chờ xác nhận
                                    </span>
                                    @break
                                @case('confirmed')
                                    <span class="badge badge-info">
                                        <i class="bi bi-check-circle me-1"></i>Đã xác nhận
                                    </span>
                                    @break
                                @case('shipped')
                                    <span class="badge badge-primary">
                                        <i class="bi bi-truck me-1"></i>Đang giao
                                    </span>
                                    @break
                                @case('delivered')
                                    <span class="badge badge-success">
                                        <i class="bi bi-check-circle-fill me-1"></i>Đã giao
                                    </span>
                                    @break
                                @case('cancelled')
                                    <span class="badge badge-danger">
                                        <i class="bi bi-x-circle me-1"></i>Đã huỷ
                                    </span>
                                    @break
                                @default
                                    <span class="badge badge-secondary">Không xác định</span>
                            @endswitch
                        </td>
                        <td>
                            <span class="text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</span>
                            <br>
                            <small class="text-muted">{{ $order->created_at->diffForHumans() }}</small>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline-info btn-sm">
                                    <i class="bi bi-eye"></i>
                                </a>
                                @if($order->order_status === 'pending')
                                    <button class="btn btn-outline-success btn-sm" onclick="updateOrderStatus({{ $order->id }}, 'confirmed')">
                                        <i class="bi bi-check"></i>
                                    </button>
                                @endif
                                @if(in_array($order->order_status, ['pending', 'confirmed']))
                                    <button class="btn btn-outline-danger btn-sm" onclick="updateOrderStatus({{ $order->id }}, 'cancelled')">
                                        <i class="bi bi-x"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="empty-state">
                                <i class="bi bi-bag"></i>
                                <h5>Chưa có đơn hàng nào</h5>
                                <p>Chưa có đơn hàng nào được tạo</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($orders->hasPages())
    <div class="admin-card-body border-top">
        <div class="d-flex justify-content-center">
            {{ $orders->appends(request()->query())->links() }}
        </div>
    </div>
    @endif
</div>

<!-- Order Statistics -->
<div class="row g-4 mt-4">
    <div class="col-md-3">
        <div class="card stats-card primary">
            <div class="card-body text-center">
                <i class="bi bi-bag-check fs-1 mb-2"></i>
                <h4>{{ $orders->total() }}</h4>
                <p class="mb-0 opacity-75">Tổng đơn hàng</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card warning">
            <div class="card-body text-center">
                <i class="bi bi-clock fs-1 mb-2"></i>
                <h4>{{ $orders->where('order_status', 'pending')->count() }}</h4>
                <p class="mb-0 opacity-75">Chờ xử lý</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card info">
            <div class="card-body text-center">
                <i class="bi bi-truck fs-1 mb-2"></i>
                <h4>{{ $orders->where('order_status', 'shipped')->count() }}</h4>
                <p class="mb-0 opacity-75">Đang giao</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card success">
            <div class="card-body text-center">
                <i class="bi bi-check-circle-fill fs-1 mb-2"></i>
                <h4>{{ $orders->where('order_status', 'delivered')->count() }}</h4>
                <p class="mb-0 opacity-75">Hoàn thành</p>
            </div>
        </div>
    </div>
</div>

<!-- Update Order Status Modal -->
<div class="modal fade" id="updateStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Cập nhật trạng thái đơn hàng</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p>Bạn có chắc chắn muốn cập nhật trạng thái đơn hàng này không?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                <button type="button" class="btn btn-primary" onclick="confirmUpdateStatus()">Xác nhận</button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra-js')
<script>
let currentOrderId = null;
let currentStatus = null;

function updateOrderStatus(orderId, status) {
    currentOrderId = orderId;
    currentStatus = status;
    
    const modal = new bootstrap.Modal(document.getElementById('updateStatusModal'));
    modal.show();
}

function confirmUpdateStatus() {
    if (currentOrderId && currentStatus) {
        fetch(`/admin/orders/${currentOrderId}/status`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ status: currentStatus })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                location.reload();
            } else {
                alert('Có lỗi xảy ra, vui lòng thử lại!');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra, vui lòng thử lại!');
        });
    }
    
    const modal = bootstrap.Modal.getInstance(document.getElementById('updateStatusModal'));
    modal.hide();
}
</script>
@endsection
