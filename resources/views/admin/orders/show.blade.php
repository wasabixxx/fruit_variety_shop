@extends('admin.layout')

@section('title', 'Chi tiết đơn hàng #' . $order->id . ' - Admin Panel')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.orders.index') }}">Quản lý Đơn hàng</a></li>
    <li class="breadcrumb-item active">Chi tiết #{{ $order->id }}</li>
@endsection

@section('content')
<!-- Page Title -->
<div class="page-title">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1>Chi tiết đơn hàng #{{ $order->id }}</h1>
            <p class="page-subtitle">Thông tin chi tiết về đơn hàng</p>
        </div>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left me-2"></i>Quay lại danh sách
        </a>
    </div>
</div>

<div class="row g-4">
    <!-- Order Information -->
    <div class="col-lg-8">
        <!-- Order Status Card -->
        <div class="admin-card card mb-4">
            <div class="admin-card-header">
                <h5 class="mb-0">
                    <i class="bi bi-info-circle me-2"></i>
                    Thông tin đơn hàng
                </h5>
            </div>
            <div class="admin-card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label text-muted">Mã đơn hàng</label>
                        <div class="fw-bold text-primary">#{{ $order->id }}</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted">Ngày đặt</label>
                        <div class="fw-semibold">{{ $order->created_at->format('d/m/Y H:i') }}</div>
                        <small class="text-muted">{{ $order->created_at->diffForHumans() }}</small>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted">Trạng thái</label>
                        <div>
                            @php
                                $statusConfig = [
                                    'pending' => ['class' => 'warning', 'icon' => 'clock', 'text' => 'Chờ xử lý'],
                                    'confirmed' => ['class' => 'info', 'icon' => 'check-circle', 'text' => 'Đã xác nhận'],
                                    'processing' => ['class' => 'primary', 'icon' => 'gear', 'text' => 'Đang xử lý'],
                                    'shipped' => ['class' => 'secondary', 'icon' => 'truck', 'text' => 'Đã giao'],
                                    'delivered' => ['class' => 'success', 'icon' => 'check-circle-fill', 'text' => 'Hoàn thành'],
                                    'cancelled' => ['class' => 'danger', 'icon' => 'x-circle', 'text' => 'Đã hủy']
                                ];
                                $config = $statusConfig[$order->order_status] ?? $statusConfig['pending'];
                            @endphp
                            <span class="badge badge-{{ $config['class'] }}">
                                <i class="bi bi-{{ $config['icon'] }} me-1"></i>{{ $config['text'] }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Phương thức thanh toán</label>
                        <div class="fw-semibold">
                            @if($order->payment_method == 'cod')
                                <i class="bi bi-cash text-success me-1"></i>Thanh toán khi nhận hàng
                            @elseif($order->payment_method == 'momo')
                                <i class="bi bi-phone text-primary me-1"></i>Ví điện tử MoMo
                            @else
                                {{ $order->payment_method }}
                            @endif
                        </div>
                        <div class="mt-1">
                            @php
                                $paymentStatusConfig = [
                                    'pending' => ['class' => 'warning', 'icon' => 'hourglass-split', 'text' => 'Chờ thanh toán'],
                                    'paid' => ['class' => 'success', 'icon' => 'check-circle', 'text' => 'Đã thanh toán'],
                                    'failed' => ['class' => 'danger', 'icon' => 'exclamation-circle', 'text' => 'Thanh toán thất bại'],
                                    'refunded' => ['class' => 'info', 'icon' => 'arrow-return-left', 'text' => 'Đã hoàn tiền']
                                ];
                                $paymentConfig = $paymentStatusConfig[$order->payment_status] ?? $paymentStatusConfig['pending'];
                            @endphp
                            <span class="badge bg-{{ $paymentConfig['class'] }} bg-opacity-10 text-{{ $paymentConfig['class'] }}">
                                <i class="bi bi-{{ $paymentConfig['icon'] }} me-1"></i>{{ $paymentConfig['text'] }}
                            </span>
                            @if($order->momo_transaction_id)
                                <div class="mt-1">
                                    <small class="text-muted">Mã giao dịch: {{ $order->momo_transaction_id }}</small>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Tổng tiền đơn hàng</label>
                        <div class="fw-bold text-success fs-5">{{ number_format($order->total_amount, 0, ',', '.') }}đ</div>
                        <div class="d-flex flex-column mt-1">
                            <small class="text-muted">
                                Tạm tính: {{ number_format($order->subtotal, 0, ',', '.') }}đ
                            </small>
                            @if($order->discount_amount > 0)
                            <small class="text-muted">
                                Giảm giá: -{{ number_format($order->discount_amount, 0, ',', '.') }}đ
                            </small>
                            @endif
                            <small class="text-muted">
                                Phí vận chuyển: {{ number_format($order->shipping_fee, 0, ',', '.') }}đ
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items -->
        <div class="admin-card card mb-4">
            <div class="admin-card-header">
                <h5 class="mb-0">
                    <i class="bi bi-basket me-2"></i>
                    Sản phẩm ({{ $order->orderItems->count() }})
                </h5>
            </div>
            <div class="admin-card-body p-0">
                <div class="table-responsive">
                    <table class="table admin-table mb-0">
                        <thead>
                            <tr>
                                <th>Sản phẩm</th>
                                <th>Giá</th>
                                <th>Số lượng</th>
                                <th class="text-end">Thành tiền</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($order->orderItems as $item)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        @if($item->product->hasImage())
                                            <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}" 
                                                 class="rounded me-3" style="width: 50px; height: 50px; object-fit: cover;">
                                        @else
                                            <div class="bg-light rounded d-flex align-items-center justify-content-center me-3" 
                                                 style="width: 50px; height: 50px;">
                                                <i class="bi bi-image text-muted"></i>
                                            </div>
                                        @endif
                                        <div>
                                            <div class="fw-semibold">{{ $item->product->name }}</div>
                                            <small class="text-muted">{{ $item->product->category->name }}</small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="fw-semibold">{{ number_format($item->price, 0, ',', '.') }}đ</span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">{{ $item->quantity }}</span>
                                </td>
                                <td class="text-end">
                                    <span class="fw-bold text-success">{{ number_format($item->price * $item->quantity, 0, ',', '.') }}đ</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="3" class="text-end">Tạm tính:</td>
                                <td class="text-end">
                                    <span class="fw-semibold">{{ number_format($order->subtotal, 0, ',', '.') }}đ</span>
                                </td>
                            </tr>
                            @if($order->discount_amount > 0)
                            <tr>
                                <td colspan="3" class="text-end">Giảm giá 
                                    @if($order->voucher_code)
                                        <span class="badge bg-primary">{{ $order->voucher_code }}</span>
                                    @endif
                                </td>
                                <td class="text-end">
                                    <span class="fw-semibold text-danger">-{{ number_format($order->discount_amount, 0, ',', '.') }}đ</span>
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <td colspan="3" class="text-end">Phí vận chuyển:</td>
                                <td class="text-end">
                                    <span class="fw-semibold">{{ number_format($order->shipping_fee, 0, ',', '.') }}đ</span>
                                </td>
                            </tr>
                            <tr class="table-light">
                                <td colspan="3" class="text-end fw-bold">Tổng cộng:</td>
                                <td class="text-end">
                                    <span class="fw-bold text-success fs-5">{{ number_format($order->total_amount, 0, ',', '.') }}đ</span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Customer Information -->
    <div class="col-lg-4">
        <!-- Customer Details -->
        <div class="admin-card card mb-4">
            <div class="admin-card-header">
                <h5 class="mb-0">
                    <i class="bi bi-person me-2"></i>
                    Thông tin khách hàng
                </h5>
            </div>
            <div class="admin-card-body">
                @if($order->user)
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3"
                             style="width: 50px; height: 50px;">
                            <i class="bi bi-person text-primary"></i>
                        </div>
                        <div>
                            <div class="fw-bold">{{ $order->user->name }}</div>
                            <small class="text-muted">{{ $order->user->email }}</small>
                        </div>
                    </div>
                @endif
                
                <div class="mb-3">
                    <label class="form-label text-muted">Họ và tên</label>
                    <div class="fw-semibold">{{ $order->customer_name }}</div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-muted">Số điện thoại</label>
                    <div class="fw-semibold">
                        <i class="bi bi-telephone me-1"></i>{{ $order->customer_phone }}
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-muted">Email</label>
                    <div class="fw-semibold">
                        <i class="bi bi-envelope me-1"></i>{{ $order->user->email ?? 'Chưa có thông tin' }}
                    </div>
                </div>
                
                <div class="mb-3">
                    <label class="form-label text-muted">Địa chỉ giao hàng</label>
                    <div class="fw-semibold">
                        <i class="bi bi-geo-alt me-1"></i>{{ $order->customer_address }}
                    </div>
                </div>
                
                @if($order->note)
                <div class="mb-0">
                    <label class="form-label text-muted">Ghi chú đơn hàng</label>
                    <div class="p-2 bg-light rounded border">
                        <i class="bi bi-chat-left-text me-1 text-muted"></i>
                        {{ $order->note }}
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Order Actions -->
        <div class="admin-card card">
            <div class="admin-card-header">
                <h5 class="mb-0">
                    <i class="bi bi-gear me-2"></i>
                    Thao tác
                </h5>
            </div>
            <div class="admin-card-body">
                <!-- Status Update Form -->
                <form action="{{ route('admin.orders.update-status', $order) }}" method="POST" class="mb-3">
                    @csrf
                    @method('PATCH')
                    
                    <!-- Order Status -->
                    <div class="mb-3">
                        <label class="form-label">Trạng thái đơn hàng</label>
                        <select name="order_status" class="form-select">
                            <option value="pending" {{ $order->order_status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                            <option value="confirmed" {{ $order->order_status == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                            <option value="processing" {{ $order->order_status == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                            <option value="shipped" {{ $order->order_status == 'shipped' ? 'selected' : '' }}>Đã giao</option>
                            <option value="delivered" {{ $order->order_status == 'delivered' ? 'selected' : '' }}>Hoàn thành</option>
                            <option value="cancelled" {{ $order->order_status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                        </select>
                    </div>
                    
                    <!-- Payment Status -->
                    <div class="mb-3">
                        <label class="form-label">Trạng thái thanh toán</label>
                        <select name="payment_status" class="form-select">
                            <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Chưa thanh toán</option>
                            <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                            <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Thanh toán thất bại</option>
                            <option value="refunded" {{ $order->payment_status == 'refunded' ? 'selected' : '' }}>Đã hoàn tiền</option>
                        </select>
                    </div>
                    
                    <button type="submit" class="btn btn-primary w-100">
                        <i class="bi bi-check-lg me-2"></i>Cập nhật trạng thái
                    </button>
                </form>

                <hr>

                <!-- Delete Order -->
                <form action="{{ route('admin.orders.delete', $order) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100"
                            onclick="return confirmDelete('Bạn có chắc chắn muốn xóa đơn hàng này?')">
                        <i class="bi bi-trash me-2"></i>Xóa đơn hàng
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
