@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="mb-1">Chi tiết đơn hàng #{{ $order->id }}</h1>
            <p class="text-muted mb-0">Đặt ngày {{ $order->created_at->format('d/m/Y lúc H:i') }}</p>
        </div>
        <a href="{{ route('orders.my-orders') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="row">
        <!-- Order Status -->
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Trạng thái đơn hàng</h5>
                </div>
                <div class="card-body">
                    @php
                        $statusColors = [
                            'pending' => 'warning',
                            'confirmed' => 'info', 
                            'processing' => 'primary',
                            'shipped' => 'secondary',
                            'delivered' => 'success',
                            'cancelled' => 'danger'
                        ];
                        $paymentColors = [
                            'pending' => 'warning',
                            'paid' => 'success',
                            'failed' => 'danger',
                            'refunded' => 'info'
                        ];
                    @endphp
                    
                    <div class="mb-3">
                        <label class="form-label"><strong>Trạng thái đơn hàng:</strong></label>
                        <div>
                            <span class="badge bg-{{ $statusColors[$order->order_status] ?? 'secondary' }} fs-6">
                                {{ $order->status_label }}
                            </span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><strong>Trạng thái thanh toán:</strong></label>
                        <div>
                            <span class="badge bg-{{ $paymentColors[$order->payment_status] ?? 'secondary' }} fs-6">
                                {{ $order->payment_status_label }}
                            </span>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label"><strong>Phương thức thanh toán:</strong></label>
                        <div>
                            @if($order->payment_method == 'cod')
                                <span class="badge bg-warning fs-6">
                                    <i class="bi bi-cash me-1"></i>Thanh toán khi nhận hàng
                                </span>
                            @elseif($order->payment_method == 'momo_atm')
                                <span class="badge bg-primary fs-6">
                                    <i class="bi bi-credit-card me-1"></i>MoMo ATM
                                </span>
                            @elseif($order->payment_method == 'momo_card')
                                <span class="badge bg-info fs-6">
                                    <i class="bi bi-credit-card-2-front me-1"></i>MoMo Card
                                </span>
                            @elseif($order->payment_method == 'momo_wallet')
                                <span class="badge bg-secondary fs-6">
                                    <img src="https://itviec.com/rails/active_storage/representations/proxy/eyJfcmFpbHMiOnsiZGF0YSI6MjA0NjgzMiwicHVyIjoiYmxvYl9pZCJ9fQ==--6d1081fa86f1300daa38e2cb2fd3ffc5a28b6592/eyJfcmFpbHMiOnsiZGF0YSI6eyJmb3JtYXQiOiJwbmciLCJyZXNpemVfdG9fbGltaXQiOlszMDAsMzAwXX0sInB1ciI6InZhcmlhdGlvbiJ9fQ==--e1d036817a0840c585f202e70291f5cdd058753d/MoMo%20Logo.png" width="16" class="me-1">MoMo Wallet
                                </span>
                            @endif
                        </div>
                    </div>

                    @if($order->momo_transaction_id)
                        <div class="mb-3">
                            <label class="form-label"><strong>Mã giao dịch:</strong></label>
                            <div>
                                <code>{{ $order->momo_transaction_id }}</code>
                            </div>
                        </div>
                    @endif

                    <div class="mt-4 p-3 bg-light rounded">
                        <h5 class="text-success mb-0">
                            Tổng tiền: {{ number_format($order->total_amount) }} VNĐ
                        </h5>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Details -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Thông tin giao hàng</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Tên người nhận:</strong> {{ $order->customer_name }}</p>
                            <p><strong>Số điện thoại:</strong> {{ $order->customer_phone }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Địa chỉ giao hàng:</strong></p>
                            <p class="text-muted">{{ $order->customer_address }}</p>
                        </div>
                    </div>
                    @if($order->note)
                        <div class="mt-3">
                            <strong>Ghi chú:</strong>
                            <p class="text-muted mb-0">{{ $order->note }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Order Items -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Sản phẩm đã đặt</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th>Đơn giá</th>
                                    <th>Số lượng</th>
                                    <th>Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->orderItems as $item)
                                <tr>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            @if($item->product && $item->product->image)
                                                <img src="{{ asset('storage/' . $item->product->image) }}" 
                                                     alt="{{ $item->product->name }}" 
                                                     class="rounded me-3" width="60">
                                            @else
                                                <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" 
                                                     style="width: 60px; height: 60px;">
                                                    <i class="bi bi-image text-muted"></i>
                                                </div>
                                            @endif
                                            <div>
                                                <div class="fw-medium">
                                                    {{ $item->product->name ?? 'Sản phẩm đã bị xóa' }}
                                                </div>
                                                @if($item->product && $item->product->category)
                                                    <small class="text-muted">{{ $item->product->category->name }}</small>
                                                @endif
                                            </div>
                                        </div>
                                    </td>
                                    <td>{{ number_format($item->price) }} VNĐ</td>
                                    <td>
                                        <span class="badge bg-light text-dark">{{ $item->quantity }}</span>
                                    </td>
                                    <td class="fw-bold text-success">{{ number_format($item->total_price) }} VNĐ</td>
                                </tr>
                                @endforeach
                            </tbody>
                            <tfoot class="table-light">
                                <tr>
                                    <th colspan="3" class="text-end">Tổng cộng:</th>
                                    <th class="text-success">{{ number_format($order->total_amount) }} VNĐ</th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Timeline -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Timeline đơn hàng</h5>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item {{ $order->order_status == 'pending' ? 'active' : ($order->order_status != 'cancelled' ? 'completed' : '') }}">
                            <i class="bi bi-clock"></i>
                            <div>
                                <h6>Đơn hàng được tạo</h6>
                                <small class="text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</small>
                            </div>
                        </div>
                        
                        <div class="timeline-item {{ $order->order_status == 'confirmed' ? 'active' : (in_array($order->order_status, ['processing', 'shipped', 'delivered']) ? 'completed' : '') }}">
                            <i class="bi bi-check-circle"></i>
                            <div>
                                <h6>Đơn hàng đã được xác nhận</h6>
                                <small class="text-muted">Chờ xử lý...</small>
                            </div>
                        </div>
                        
                        <div class="timeline-item {{ $order->order_status == 'processing' ? 'active' : (in_array($order->order_status, ['shipped', 'delivered']) ? 'completed' : '') }}">
                            <i class="bi bi-gear"></i>
                            <div>
                                <h6>Đang chuẩn bị hàng</h6>
                                <small class="text-muted">Đang đóng gói...</small>
                            </div>
                        </div>
                        
                        <div class="timeline-item {{ $order->order_status == 'shipped' ? 'active' : ($order->order_status == 'delivered' ? 'completed' : '') }}">
                            <i class="bi bi-truck"></i>
                            <div>
                                <h6>Đang giao hàng</h6>
                                <small class="text-muted">Hàng đang trên đường giao...</small>
                            </div>
                        </div>
                        
                        <div class="timeline-item {{ $order->order_status == 'delivered' ? 'completed' : '' }}">
                            <i class="bi bi-house-check"></i>
                            <div>
                                <h6>Đã giao hàng thành công</h6>
                                <small class="text-muted">Cảm ơn bạn đã mua hàng!</small>
                            </div>
                        </div>
                        
                        @if($order->order_status == 'cancelled')
                        <div class="timeline-item cancelled">
                            <i class="bi bi-x-circle"></i>
                            <div>
                                <h6>Đơn hàng đã bị hủy</h6>
                                <small class="text-muted">{{ $order->updated_at->format('d/m/Y H:i') }}</small>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding: 20px 0;
}

.timeline-item {
    position: relative;
    padding-left: 50px;
    margin-bottom: 30px;
    border-left: 2px solid #dee2e6;
}

.timeline-item:last-child {
    border-left: none;
}

.timeline-item i {
    position: absolute;
    left: -10px;
    top: 0;
    width: 20px;
    height: 20px;
    background: #dee2e6;
    color: white;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content-center;
    font-size: 12px;
}

.timeline-item.completed i {
    background: #198754;
}

.timeline-item.active i {
    background: #0d6efd;
}

.timeline-item.cancelled i {
    background: #dc3545;
}

.timeline-item h6 {
    margin-bottom: 5px;
    font-weight: 600;
}

.timeline-item.completed h6 {
    color: #198754;
}

.timeline-item.active h6 {
    color: #0d6efd;
}

.timeline-item.cancelled h6 {
    color: #dc3545;
}
</style>
@endsection
