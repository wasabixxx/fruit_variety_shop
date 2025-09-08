@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4"><i class="bi bi-clock-history me-2"></i>Lịch sử đơn hàng của tôi</h1>
    
    @if($orders->count() > 0)
        <div class="row">
            @foreach($orders as $order)
            <div class="col-md-6 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <strong>Đơn hàng #{{ $order->id }}</strong>
                        <small class="text-muted">{{ $order->created_at->format('d/m/Y H:i') }}</small>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-6">
                                <small class="text-muted">Trạng thái đơn hàng:</small>
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
                                <div>
                                    <span class="badge bg-{{ $statusColors[$order->order_status] ?? 'secondary' }}">
                                        {{ $order->status_label }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-6">
                                <small class="text-muted">Thanh toán:</small>
                                @php
                                    $paymentColors = [
                                        'pending' => 'warning',
                                        'paid' => 'success',
                                        'failed' => 'danger',
                                        'refunded' => 'info'
                                    ];
                                @endphp
                                <div>
                                    <span class="badge bg-{{ $paymentColors[$order->payment_status] ?? 'secondary' }}">
                                        {{ $order->payment_status_label }}
                                    </span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <small class="text-muted">Sản phẩm:</small>
                            <div class="mt-1">
                                @foreach($order->orderItems->take(3) as $item)
                                    <div class="d-flex justify-content-between align-items-center mb-1">
                                        <span class="small">{{ $item->product->name ?? 'Sản phẩm đã xóa' }} x{{ $item->quantity }}</span>
                                        <span class="small text-success">{{ number_format($item->total_price) }} VNĐ</span>
                                    </div>
                                @endforeach
                                @if($order->orderItems->count() > 3)
                                    <small class="text-muted">... và {{ $order->orderItems->count() - 3 }} sản phẩm khác</small>
                                @endif
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <span><strong>Tổng cộng:</strong></span>
                            <span class="h5 text-success mb-0">{{ number_format($order->total_amount) }} VNĐ</span>
                        </div>

                        <div class="mb-2">
                            <small class="text-muted">Phương thức thanh toán:</small>
                            <div>
                                @if($order->payment_method == 'cod')
                                    <i class="bi bi-cash text-warning me-1"></i>Thanh toán khi nhận hàng
                                @elseif($order->payment_method == 'momo_atm')
                                    <i class="bi bi-credit-card text-primary me-1"></i>MoMo ATM
                                @elseif($order->payment_method == 'momo_card')
                                    <i class="bi bi-credit-card-2-front text-info me-1"></i>MoMo Card
                                @elseif($order->payment_method == 'momo_wallet')
                                    <img src="https://itviec.com/rails/active_storage/representations/proxy/eyJfcmFpbHMiOnsiZGF0YSI6MjA0NjgzMiwicHVyIjoiYmxvYl9pZCJ9fQ==--6d1081fa86f1300daa38e2cb2fd3ffc5a28b6592/eyJfcmFpbHMiOnsiZGF0YSI6eyJmb3JtYXQiOiJwbmciLCJyZXNpemVfdG9fbGltaXQiOlszMDAsMzAwXX0sInB1ciI6InZhcmlhdGlvbiJ9fQ==--e1d036817a0840c585f202e70291f5cdd058753d/MoMo%20Logo.png" width="16" class="me-1">MoMo Wallet
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-footer bg-transparent">
                        <a href="{{ route('orders.my-order-detail', $order) }}" class="btn btn-outline-primary btn-sm w-100">
                            <i class="bi bi-eye me-1"></i>Xem chi tiết
                        </a>
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center">
            {{ $orders->links() }}
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-cart-x display-1 text-muted mb-3"></i>
            <h3 class="text-muted">Bạn chưa có đơn hàng nào</h3>
            <p class="text-muted">Hãy mua sắm ngay để tạo đơn hàng đầu tiên!</p>
            <a href="{{ route('products.index') }}" class="btn btn-primary">
                <i class="bi bi-shop me-2"></i>Mua sắm ngay
            </a>
        </div>
    @endif
</div>
@endsection
