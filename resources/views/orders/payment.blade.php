@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4"><i class="bi bi-qr-code"></i> Thanh toán bằng QR</h1>
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-info text-white">
                    <i class="bi bi-credit-card"></i> Thông tin thanh toán
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Thông tin đơn hàng:</h5>
                            <ul class="list-unstyled">
                                <li><strong>Khách hàng:</strong> {{ $orderInfo['customer_name'] }}</li>
                                <li><strong>Điện thoại:</strong> {{ $orderInfo['customer_phone'] }}</li>
                                <li><strong>Địa chỉ:</strong> {{ $orderInfo['customer_address'] }}</li>
                                <li><strong>Thời gian:</strong> {{ $orderInfo['order_time'] }}</li>
                            </ul>
                            <h6 class="mt-3">Chi tiết đơn hàng:</h6>
                            <ul class="list-group list-group-flush">
                                @foreach($orderInfo['items'] as $item)
                                <li class="list-group-item d-flex justify-content-between px-0">
                                    <span>{{ $item['name'] }} x{{ $item['quantity'] }}</span>
                                    <span>{{ number_format($item['price'] * $item['quantity']) }} VNĐ</span>
                                </li>
                                @endforeach
                            </ul>
                            <div class="text-end mt-2">
                                <h4>Tổng: <span class="text-success">{{ number_format($orderInfo['total']) }} VNĐ</span></h4>
                            </div>
                        </div>
                        <div class="col-md-6 text-center">
                            <h5 class="mb-3">Quét mã QR để thanh toán:</h5>
                            <img src="https://img.vietqr.io/image/MB-446619999-print.png?amount={{ $orderInfo['total'] }}&addInfo=Thanh%20toan%20FRUIT%20VARIETY%20SHOP%20{{ urlencode($orderInfo['order_time']) }}&accountName=NGUYEN%20NGOC%20KHANH" alt="QR Code" width="300" height="auto" class="mb-3 rounded shadow">
                            <p class="text-muted small">Quét mã bằng app ngân hàng để chuyển khoản đúng số tiền và nội dung.</p>
                            <form method="POST" action="{{ route('orders.confirm-payment') }}">
                                @csrf
                                <button type="submit" class="btn btn-success btn-lg w-100 mt-2">
                                    <i class="bi bi-check-circle"></i> Tôi đã chuyển khoản
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mt-4 text-center">
        <a href="{{ route('orders.create') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>
</div>
@endsection
