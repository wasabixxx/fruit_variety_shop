@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4"><i class="bi bi-phone"></i> Thanh toán bằng MoMo</h1>
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header text-white" style="background-color: #a50064;">
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
                        <div class="col-md-6">
                            <div class="text-center mb-3">
                                <img src="https://itviec.com/rails/active_storage/representations/proxy/eyJfcmFpbHMiOnsiZGF0YSI6MjA0NjgzMiwicHVyIjoiYmxvYl9pZCJ9fQ==--6d1081fa86f1300daa38e2cb2fd3ffc5a28b6592/eyJfcmFpbHMiOnsiZGF0YSI6eyJmb3JtYXQiOiJwbmciLCJyZXNpemVfdG9fbGltaXQiOlszMDAsMzAwXX0sInB1ciI6InZhcmlhdGlvbiJ9fQ==--e1d036817a0840c585f202e70291f5cdd058753d/MoMo%20Logo.png" alt="MoMo" width="80">
                                @if($paymentMethod === 'atm')
                                    <h5 class="mt-2 text-primary">Thanh toán MoMo - Thẻ ATM/Internet Banking</h5>
                                @elseif($paymentMethod === 'card')
                                    <h5 class="mt-2 text-info">Thanh toán MoMo - Thẻ Visa/MasterCard</h5>
                                @elseif($paymentMethod === 'wallet')
                                    <h5 class="mt-2" style="color: #a50064;">Thanh toán MoMo - Ví điện tử MoMo</h5>
                                @else
                                    <h5 class="mt-2">Thanh toán MoMo Test</h5>
                                @endif
                            </div>

                            @if($paymentMethod === 'atm')
                                <div class="alert alert-info">
                                    <h6><i class="bi bi-info-circle"></i> Thẻ ATM Test có sẵn:</h6>
                                    <small>
                                        <table class="table table-sm mb-2">
                                            <thead>
                                                <tr>
                                                    <th>Số thẻ</th>
                                                    <th>Kết quả</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="text-success">
                                                    <td>9704 0000 0000 0018</td>
                                                    <td>✓ Thành công</td>
                                                </tr>
                                                <tr class="text-danger">
                                                    <td>9704 0000 0000 0026</td>
                                                    <td>✗ Thẻ khóa</td>
                                                </tr>
                                                <tr class="text-warning">
                                                    <td>9704 0000 0000 0034</td>
                                                    <td>⚠ Không đủ tiền</td>
                                                </tr>
                                                <tr class="text-danger">
                                                    <td>9704 0000 0000 0042</td>
                                                    <td>✗ Hạn mức</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <strong>Tên:</strong> NGUYEN VAN A<br>
                                        <strong>Hạn thẻ:</strong> 03/07<br>
                                        <strong>OTP:</strong> Sử dụng OTP được cung cấp
                                    </small>
                                </div>
                            @elseif($paymentMethod === 'card')
                                <div class="alert alert-info">
                                    <h6><i class="bi bi-info-circle"></i> Thẻ Visa/MasterCard Test:</h6>
                                    <small>
                                        <table class="table table-sm mb-2">
                                            <thead>
                                                <tr>
                                                    <th>Số thẻ</th>
                                                    <th>Loại</th>
                                                    <th>Kết quả</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="text-success">
                                                    <td>5555 5555 5555 4444</td>
                                                    <td>MasterCard</td>
                                                    <td>✓ Thành công</td>
                                                </tr>
                                                <tr class="text-success">
                                                    <td>4111 1111 1111 1111</td>
                                                    <td>Visa</td>
                                                    <td>✓ Thành công</td>
                                                </tr>
                                                <tr class="text-danger">
                                                    <td>4000 0000 0000 0002</td>
                                                    <td>Visa</td>
                                                    <td>✗ Thẻ bị từ chối</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <strong>CVV:</strong> 123<br>
                                        <strong>Hạn thẻ:</strong> 12/25<br>
                                        <strong>Tên:</strong> Test Card
                                    </small>
                                </div>
                            @elseif($paymentMethod === 'wallet')
                                <div class="alert alert-info">
                                    <h6><i class="bi bi-info-circle"></i> MoMo Wallet Test:</h6>
                                    <small>
                                        <table class="table table-sm mb-2">
                                            <thead>
                                                <tr>
                                                    <th>Số điện thoại</th>
                                                    <th>Kết quả</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr class="text-success">
                                                    <td>0999 999 999</td>
                                                    <td>✓ Thanh toán thành công</td>
                                                </tr>
                                                <tr class="text-warning">
                                                    <td>0888 888 888</td>
                                                    <td>⚠ Số dư không đủ</td>
                                                </tr>
                                                <tr class="text-danger">
                                                    <td>0777 777 777</td>
                                                    <td>✗ Ví bị khóa</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                        <strong>OTP:</strong> 123456<br>
                                        <strong>PIN:</strong> 1234
                                    </small>
                                </div>
                            @endif

                            @if(isset($jsonResult['payUrl']) && $jsonResult['payUrl'])
                                <div class="alert alert-success">
                                    <h6><i class="bi bi-check-circle"></i> Thanh toán đã sẵn sàng!</h6>
                                    <p class="mb-0">Nhấn nút bên dưới để chuyển đến trang thanh toán MoMo.</p>
                                </div>
                                
                                <a href="{{ $jsonResult['payUrl'] }}" class="btn btn-lg w-100 text-white" style="background-color: #a50064;" target="_blank">
                                    @if($paymentMethod === 'atm')
                                        <i class="bi bi-credit-card"></i> Thanh toán bằng ATM
                                    @elseif($paymentMethod === 'card') 
                                        <i class="bi bi-credit-card-2-front"></i> Thanh toán bằng Visa/MasterCard
                                    @elseif($paymentMethod === 'wallet')
                                        <i class="bi bi-wallet2"></i> Thanh toán bằng Ví MoMo
                                    @else
                                        <i class="bi bi-credit-card"></i> Thanh toán MoMo Test
                                    @endif
                                </a>
                            @elseif(isset($jsonResult['message']))
                                <div class="alert alert-warning">
                                    <h6><i class="bi bi-exclamation-triangle"></i> Thông báo từ MoMo</h6>
                                    <p class="mb-2">{{ $jsonResult['message'] ?? 'Lỗi không xác định' }}</p>
                                    <small>Mã lỗi: {{ $jsonResult['resultCode'] ?? 'N/A' }}</small>
                                </div>
                                
                                <!-- Fallback: Sử dụng direct form submit -->
                                <form method="POST" action="{{ env('MOMO_ENDPOINT') }}" target="_blank" id="momoForm">
                                    @foreach($data as $key => $value)
                                        <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                    @endforeach
                                    
                                    <button type="submit" class="btn btn-lg w-100 text-white" style="background-color: #a50064;">
                                        @if($paymentMethod === 'atm')
                                            <i class="bi bi-credit-card"></i> Thử thanh toán ATM trực tiếp
                                        @elseif($paymentMethod === 'card')
                                            <i class="bi bi-credit-card-2-front"></i> Thử thanh toán Visa/MC trực tiếp  
                                        @elseif($paymentMethod === 'wallet')
                                            <i class="bi bi-wallet2"></i> Thử thanh toán Ví MoMo trực tiếp
                                        @else
                                            <i class="bi bi-credit-card"></i> Thử thanh toán trực tiếp
                                        @endif
                                    </button>
                                </form>
                            @else
                                <div class="alert alert-warning">
                                    <h6><i class="bi bi-exclamation-triangle"></i> Không kết nối được MoMo API</h6>
                                    <p class="mb-0">Hệ thống sẽ sử dụng phương thức test thay thế.</p>
                                </div>
                                
                                <a href="{{ route('orders.payment-fallback') }}" class="btn btn-lg w-100 text-white" style="background-color: #a50064;">
                                    @if($paymentMethod === 'atm')
                                        <i class="bi bi-play-circle"></i> Test ATM Thay Thế
                                    @elseif($paymentMethod === 'card')
                                        <i class="bi bi-play-circle"></i> Test Visa/MC Thay Thế
                                    @elseif($paymentMethod === 'wallet') 
                                        <i class="bi bi-play-circle"></i> Test Ví MoMo Thay Thế
                                    @else
                                        <i class="bi bi-play-circle"></i> Sử dụng MoMo Test Thay Thế
                                    @endif
                                </a>
                            @endif

                            <p class="text-muted text-center mt-2 small">
                                Bạn sẽ được chuyển đến trang test MoMo để thực hiện thanh toán với thẻ ATM test
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
