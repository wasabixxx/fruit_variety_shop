@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4"><i class="bi bi-receipt"></i> Xác nhận đơn hàng</h1>
    <div class="row">
        <div class="col-lg-7 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-success text-white">
                    <i class="bi bi-basket"></i> Thông tin đơn hàng
                </div>
                <div class="card-body">
                    <ul class="list-group mb-3">
                        @foreach($cart as $item)
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <div>
                                <strong>{{ $item['name'] }}</strong>
                                <span class="text-muted">x{{ $item['quantity'] }}</span>
                            </div>
                            <span>{{ number_format($item['price'] * $item['quantity']) }} VNĐ</span>
                        </li>
                        @endforeach
                    </ul>
                    <div class="text-end">
                        <h4>Tổng cộng: <span class="text-success">{{ number_format($total) }} VNĐ</span></h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-5 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <i class="bi bi-credit-card"></i> Thông tin giao hàng & thanh toán
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('orders.store') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="customer_name" class="form-label">Họ và tên *</label>
                            <input type="text" class="form-control" id="customer_name" name="customer_name" required>
                        </div>
                        <div class="mb-3">
                            <label for="customer_phone" class="form-label">Số điện thoại *</label>
                            <input type="tel" class="form-control" id="customer_phone" name="customer_phone" required>
                        </div>
                        <div class="mb-3">
                            <label for="customer_address" class="form-label">Địa chỉ giao hàng *</label>
                            <textarea class="form-control" id="customer_address" name="customer_address" rows="3" required></textarea>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Phương thức thanh toán *</label>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod" checked>
                                <label class="form-check-label" for="cod">
                                    <i class="bi bi-cash"></i> Thanh toán khi nhận hàng (COD)
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="payment_method" id="qr" value="qr">
                                <label class="form-check-label" for="qr">
                                    <i class="bi bi-qr-code"></i> Thanh toán ngay bằng QR
                                </label>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-success btn-lg w-100">
                            <i class="bi bi-check-circle"></i> Xác nhận đặt hàng
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-4">
        <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại giỏ hàng
        </a>
    </div>
</div>
@endsection
