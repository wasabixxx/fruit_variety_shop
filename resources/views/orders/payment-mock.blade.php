@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4"><i class="bi bi-phone"></i> Thanh toán MoMo Test Simulation</h1>
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header text-white" style="background-color: #a50064;">
                    <i class="bi bi-credit-card"></i> Mô phỏng thanh toán MoMo
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
                        <div class="col-md-6">
                            <div class="text-center mb-3">
                                <img src="https://itviec.com/rails/active_storage/representations/proxy/eyJfcmFpbHMiOnsiZGF0YSI6MjA0NjgzMiwicHVyIjoiYmxvYl9pZCJ9fQ==--6d1081fa86f1300daa38e2cb2fd3ffc5a28b6592/eyJfcmFpbHMiOnsiZGF0YSI6eyJmb3JtYXQiOiJwbmciLCJyZXNpemVfdG9fbGltaXQiOlszMDAsMzAwXX0sInB1ciI6InZhcmlhdGlvbiJ9fQ==--e1d036817a0840c585f202e70291f5cdd058753d/MoMo%20Logo.png" alt="MoMo" width="80">
                                @if(isset($paymentMethod) && $paymentMethod === 'atm')
                                    <h5 class="mt-2 text-primary">Mô phỏng ATM/Internet Banking</h5>
                                @elseif(isset($paymentMethod) && $paymentMethod === 'card')
                                    <h5 class="mt-2 text-info">Mô phỏng Visa/MasterCard</h5>
                                @elseif(isset($paymentMethod) && $paymentMethod === 'wallet')
                                    <h5 class="mt-2" style="color: #a50064;">Mô phỏng Ví MoMo</h5>
                                @else
                                    <h5 class="mt-2">Mô phỏng thanh toán MoMo</h5>
                                @endif
                            </div>

                            <div class="alert alert-info">
                                <h6><i class="bi bi-info-circle"></i> Chọn kết quả thanh toán test:</h6>
                                <p class="small mb-2">Vì đây là môi trường test, bạn có thể chọn kết quả mong muốn:</p>
                            </div>

                            <form method="GET" action="{{ route('orders.momo-return') }}" id="momoTestForm">
                                <div class="mb-3">
                                    <label class="form-label">Chọn kết quả test:</label>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="resultCode" value="0" id="success" checked>
                                        <label class="form-check-label text-success" for="success">
                                            <strong>✓ Thanh toán thành công</strong> - Thẻ 9704 0000 0000 0018
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="resultCode" value="10" id="locked">
                                        <label class="form-check-label text-danger" for="locked">
                                            <strong>✗ Thẻ bị khóa</strong> - Thẻ 9704 0000 0000 0026
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="resultCode" value="13" id="insufficient">
                                        <label class="form-check-label text-warning" for="insufficient">
                                            <strong>⚠ Không đủ tiền</strong> - Thẻ 9704 0000 0000 0034
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="resultCode" value="21" id="limit">
                                        <label class="form-check-label text-danger" for="limit">
                                            <strong>✗ Vượt hạn mức</strong> - Thẻ 9704 0000 0000 0042
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="resultCode" value="9" id="cancel">
                                        <label class="form-check-label text-secondary" for="cancel">
                                            <strong>↶ Hủy thanh toán</strong> - Người dùng hủy
                                        </label>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-lg w-100 text-white" style="background-color: #a50064;">
                                    <i class="bi bi-play-circle"></i> Mô phỏng thanh toán MoMo
                                </button>
                            </form>

                            <p class="text-muted text-center mt-2 small">
                                Đây là môi trường test - chọn kết quả để mô phỏng thanh toán
                            </p>
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
