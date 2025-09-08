@extends('admin.layouts.app')

@section('title', 'Chi tiết đơn hàng #' . $order->id)

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Chi tiết đơn hàng #{{ $order->id }}</h1>
            <p class="text-muted mb-0">Đặt ngày {{ $order->created_at->format('d/m/Y lúc H:i') }}</p>
        </div>
        <a href="{{ route('admin.orders') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="row">
        <!-- Order Info -->
        <div class="col-md-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Thông tin khách hàng</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Tên:</strong> {{ $order->customer_name }}</p>
                            <p><strong>Điện thoại:</strong> {{ $order->customer_phone }}</p>
                            <p><strong>Địa chỉ:</strong> {{ $order->customer_address }}</p>
                        </div>
                        <div class="col-md-6">
                            @if($order->user)
                                <p><strong>Tài khoản:</strong> {{ $order->user->email }}</p>
                            @else
                                <p><strong>Tài khoản:</strong> <em>Khách vãng lai</em></p>
                            @endif
                            @if($order->note)
                                <p><strong>Ghi chú:</strong> {{ $order->note }}</p>
                            @endif
                        </div>
                    </div>
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
                                                     alt="{{ $item->product->name ?? 'Sản phẩm đã xóa' }}" 
                                                     class="rounded me-3" width="50">
                                            @else
                                                <div class="bg-light rounded me-3 d-flex align-items-center justify-content-center" 
                                                     style="width: 50px; height: 50px;">
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
                                    <td>{{ $item->quantity }}</td>
                                    <td class="fw-bold">{{ number_format($item->total_price) }} VNĐ</td>
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

        <!-- Order Status -->
        <div class="col-md-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">Trạng thái đơn hàng</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.orders.update-status', $order) }}">
                        @csrf
                        @method('PATCH')
                        
                        <div class="mb-3">
                            <label class="form-label">Trạng thái đơn hàng</label>
                            <select name="order_status" class="form-select">
                                <option value="pending" {{ $order->order_status == 'pending' ? 'selected' : '' }}>Chờ xử lý</option>
                                <option value="confirmed" {{ $order->order_status == 'confirmed' ? 'selected' : '' }}>Đã xác nhận</option>
                                <option value="processing" {{ $order->order_status == 'processing' ? 'selected' : '' }}>Đang xử lý</option>
                                <option value="shipped" {{ $order->order_status == 'shipped' ? 'selected' : '' }}>Đang giao hàng</option>
                                <option value="delivered" {{ $order->order_status == 'delivered' ? 'selected' : '' }}>Đã giao hàng</option>
                                <option value="cancelled" {{ $order->order_status == 'cancelled' ? 'selected' : '' }}>Đã hủy</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Trạng thái thanh toán</label>
                            <select name="payment_status" class="form-select">
                                <option value="pending" {{ $order->payment_status == 'pending' ? 'selected' : '' }}>Chờ thanh toán</option>
                                <option value="paid" {{ $order->payment_status == 'paid' ? 'selected' : '' }}>Đã thanh toán</option>
                                <option value="failed" {{ $order->payment_status == 'failed' ? 'selected' : '' }}>Thanh toán thất bại</option>
                                <option value="refunded" {{ $order->payment_status == 'refunded' ? 'selected' : '' }}>Đã hoàn tiền</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-check-circle"></i> Cập nhật trạng thái
                        </button>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Thông tin thanh toán</h5>
                </div>
                <div class="card-body">
                    <p><strong>Phương thức:</strong> 
                        @if($order->payment_method == 'cod')
                            <span class="badge bg-warning">Thanh toán khi nhận hàng</span>
                        @elseif($order->payment_method == 'momo_atm')
                            <span class="badge bg-primary">MoMo ATM</span>
                        @elseif($order->payment_method == 'momo_card')
                            <span class="badge bg-info">MoMo Card</span>
                        @elseif($order->payment_method == 'momo_wallet')
                            <span class="badge bg-secondary">MoMo Wallet</span>
                        @endif
                    </p>
                    
                    @if($order->momo_transaction_id)
                        <p><strong>Mã giao dịch MoMo:</strong> {{ $order->momo_transaction_id }}</p>
                    @endif

                    <p><strong>Tổng tiền:</strong> <span class="text-success fw-bold">{{ number_format($order->total_amount) }} VNĐ</span></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
