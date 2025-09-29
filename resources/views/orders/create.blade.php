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
                    
                    <!-- Voucher Summary -->
                    @if($appliedVoucher && $discountAmount > 0)
                    <div class="border-top pt-3 mb-3">
                        <div class="d-flex justify-content-between text-muted mb-2">
                            <span>Tạm tính:</span>
                            <span>{{ number_format($subtotal, 0, ',', '.') }}đ</span>
                        </div>
                        <div class="d-flex justify-content-between text-success mb-2">
                            <div>
                                <i class="bi bi-ticket-perforated me-1"></i>
                                <span>Giảm giá ({{ $appliedVoucher['code'] }}):</span>
                            </div>
                            <span>-{{ number_format($discountAmount, 0, ',', '.') }}đ</span>
                        </div>
                    </div>
                    @endif
                    
                    <div class="text-end">
                        <h5>Tạm tính: <span class="text-muted">{{ number_format($total, 0, ',', '.') }}đ</span></h5>
                        <h4>Tổng cộng: <span class="text-success" id="total_with_shipping">{{ number_format($total, 0, ',', '.') }}đ</span></h4>
                        <!-- Hidden field to store original total for JS -->
                        <input type="hidden" id="order_total" data-total="{{ $total }}">
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
                            <label class="form-label">Địa chỉ giao hàng *</label>
                            <div class="row g-2 mb-2">
                                <div class="col-12">
                                    <select class="form-select" id="province" name="province" required>
                                        <option value="">Chọn Tỉnh/Thành phố</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <select class="form-select" id="district" name="district" required disabled>
                                        <option value="">Chọn Quận/Huyện</option>
                                    </select>
                                </div>
                                <div class="col-md-6">
                                    <select class="form-select" id="ward" name="ward" required disabled>
                                        <option value="">Chọn Phường/Xã</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <input type="text" class="form-control" id="address_detail" name="address_detail" 
                                           placeholder="Số nhà, tên đường" required>
                                </div>
                            </div>
                            <input type="hidden" id="full_address" name="customer_address" value="">
                            <input type="hidden" id="shipping_fee" name="shipping_fee" value="0">
                        </div>
                        
                        <!-- Shipping Fee Information -->
                        <div class="alert alert-info">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <i class="bi bi-truck"></i> Phí vận chuyển:
                                </div>
                                <span id="shipping_fee_amount">0₫</span>
                            </div>
                        </div>
                        <div class="mb-4">
                            <label class="form-label">Phương thức thanh toán *</label>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="payment_method" id="cod" value="cod" checked>
                                <label class="form-check-label" for="cod">
                                    <i class="bi bi-cash text-success me-2"></i> Thanh toán khi nhận hàng (COD)
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="payment_method" id="momo_atm" value="momo_atm">
                                <label class="form-check-label" for="momo_atm">
                                    <i class="bi bi-credit-card text-primary me-2"></i> MoMo - Thẻ ATM/Internet Banking
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="payment_method" id="momo_card" value="momo_card">
                                <label class="form-check-label" for="momo_card">
                                    <i class="bi bi-credit-card-2-front text-info me-2"></i> MoMo - Thẻ Visa/MasterCard
                                </label>
                            </div>
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="payment_method" id="momo_wallet" value="momo_wallet">
                                <label class="form-check-label" for="momo_wallet">
                                    <img src="https://itviec.com/rails/active_storage/representations/proxy/eyJfcmFpbHMiOnsiZGF0YSI6MjA0NjgzMiwicHVyIjoiYmxvYl9pZCJ9fQ==--6d1081fa86f1300daa38e2cb2fd3ffc5a28b6592/eyJfcmFpbHMiOnsiZGF0YSI6eyJmb3JtYXQiOiJwbmciLCJyZXNpemVfdG9fbGltaXQiOlszMDAsMzAwXX0sInB1ciI6InZhcmlhdGlvbiJ9fQ==--e1d036817a0840c585f202e70291f5cdd058753d/MoMo%20Logo.png" width="20" class="me-2"> MoMo - Ví điện tử MoMo
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

@push('scripts')
<script src="{{ asset('js/address.js') }}"></script>
@endpush
