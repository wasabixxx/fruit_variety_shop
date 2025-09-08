@extends('layouts.app')

@section('title', 'Giỏ hàng - Fruit Variety Shop')

@section('content')
<!-- Page Header -->
<div class="mb-5">
    <div class="d-flex align-items-center justify-content-between">
        <div>
            <h1 class="h2 fw-bold mb-2">
                <i class="bi bi-cart4 text-primary me-2"></i>Giỏ hàng của bạn
            </h1>
            <p class="text-muted mb-0">
                @if(count($cart) > 0)
                    Bạn có {{ count($cart) }} sản phẩm trong giỏ hàng
                @else
                    Giỏ hàng hiện tại đang trống
                @endif
            </p>
        </div>
        
        @if(count($cart) > 0)
            <div class="d-flex gap-2">
                <form method="POST" action="{{ route('cart.clear') }}">
                    @csrf
                    <button type="submit" class="btn btn-outline-danger" 
                            onclick="return confirm('Bạn có chắc chắn muốn xóa toàn bộ giỏ hàng?')">
                        <i class="bi bi-trash me-1"></i>Xóa tất cả
                    </button>
                </form>
            </div>
        @endif
    </div>
</div>

@if(count($cart) > 0)
    <div class="row g-4">
        <!-- Cart Items -->
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-bottom-0 py-3">
                    <h5 class="fw-bold mb-0">
                        <i class="bi bi-bag me-2"></i>Chi tiết sản phẩm
                    </h5>
                </div>
                <div class="card-body p-0">
                    @foreach($cart as $item)
                    <div class="cart-item border-bottom p-4 {{ !$loop->last ? 'border-bottom' : '' }}">
                        <div class="row align-items-center">
                            <!-- Product Image -->
                            <div class="col-md-2 col-3 mb-3 mb-md-0">
                                @if($item['image'])
                                    <img src="{{ $item['image'] }}" 
                                         alt="{{ $item['name'] }}" 
                                         class="img-fluid rounded-3 shadow-sm"
                                         style="height: 80px; width: 100%; object-fit: cover;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center rounded-3" 
                                         style="height: 80px;">
                                        <i class="bi bi-image text-muted fs-3"></i>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Product Info -->
                            <div class="col-md-4 col-9 mb-3 mb-md-0">
                                <h6 class="fw-bold mb-1">{{ $item['name'] }}</h6>
                                <div class="d-flex align-items-center text-muted small">
                                    <i class="bi bi-check-circle me-1"></i>
                                    @if($item['stock'] > 0)
                                        Còn {{ $item['stock'] }} sản phẩm
                                    @else
                                        <span class="text-danger">Hết hàng</span>
                                    @endif
                                </div>
                            </div>
                            
                            <!-- Price -->
                            <div class="col-md-2 col-4 mb-3 mb-md-0 text-center">
                                <div class="fw-bold text-primary fs-6">
                                    {{ number_format($item['price'], 0, ',', '.') }}đ
                                </div>
                                <small class="text-muted">/ kg</small>
                            </div>
                            
                            <!-- Quantity Controls -->
                            <div class="col-md-3 col-4 mb-3 mb-md-0">
                                <form method="POST" action="{{ route('cart.update', $item['id']) }}" 
                                      class="d-flex align-items-center justify-content-center">
                                    @csrf
                                    <div class="input-group" style="max-width: 120px;">
                                        <button type="button" class="btn btn-outline-secondary btn-sm quantity-btn" 
                                                onclick="decreaseQuantity(this)">
                                            <i class="bi bi-dash"></i>
                                        </button>
                                        <input type="number" name="quantity" 
                                               value="{{ $item['quantity'] }}" 
                                               min="1" max="{{ $item['stock'] }}" 
                                               class="form-control form-control-sm text-center quantity-input">
                                        <button type="button" class="btn btn-outline-secondary btn-sm quantity-btn" 
                                                onclick="increaseQuantity(this, {{ $item['stock'] }})">
                                            <i class="bi bi-plus"></i>
                                        </button>
                                    </div>
                                    <button type="submit" class="btn btn-primary btn-sm ms-2">
                                        <i class="bi bi-arrow-clockwise"></i>
                                    </button>
                                </form>
                            </div>
                            
                            <!-- Subtotal & Remove -->
                            <div class="col-md-1 col-4 text-end">
                                <div class="fw-bold text-dark mb-2">
                                    {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}đ
                                </div>
                                <form method="POST" action="{{ route('cart.remove', $item['id']) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger btn-sm" 
                                            onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        
        <!-- Order Summary -->
        <div class="col-lg-4">
            <div class="sticky-top" style="top: 100px;">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-bottom-0 py-3">
                        <h5 class="fw-bold mb-0">
                            <i class="bi bi-receipt me-2"></i>Tóm tắt đơn hàng
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Summary Items -->
                        <div class="mb-4">
                            @foreach($cart as $item)
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="small text-muted">
                                    {{ Str::limit($item['name'], 20) }} x{{ $item['quantity'] }}
                                </div>
                                <div class="small fw-semibold">
                                    {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}đ
                                </div>
                            </div>
                            @endforeach
                        </div>
                        
                        <hr>
                        
                        <!-- Pricing Breakdown -->
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Tạm tính:</span>
                            <span class="fw-semibold">{{ number_format($total, 0, ',', '.') }}đ</span>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Phí vận chuyển:</span>
                            <span class="fw-semibold text-success">Miễn phí</span>
                        </div>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="fw-bold fs-5">Tổng cộng:</span>
                            <span class="fw-bold fs-4 text-primary">{{ number_format($total, 0, ',', '.') }}đ</span>
                        </div>
                        
                        <!-- Action Buttons -->
                        <div class="d-grid gap-2">
                            <a href="{{ route('orders.create') }}" class="btn btn-primary btn-lg">
                                <i class="bi bi-credit-card me-2"></i>Tiến hành thanh toán
                            </a>
                            <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                                <i class="bi bi-arrow-left me-2"></i>Tiếp tục mua sắm
                            </a>
                        </div>
                        
                        <!-- Payment Methods -->
                        <div class="mt-4 pt-3 border-top">
                            <div class="text-center">
                                <small class="text-muted d-block mb-2">Phương thức thanh toán</small>
                                <div class="d-flex justify-content-center align-items-center gap-2">
                                    <img src="https://itviec.com/rails/active_storage/representations/proxy/eyJfcmFpbHMiOnsiZGF0YSI6MjA0NjgzMiwicHVyIjoiYmxvYl9pZCJ9fQ==--6d1081fa86f1300daa38e2cb2fd3ffc5a28b6592/eyJfcmFpbHMiOnsiZGF0YSI6eyJmb3JtYXQiOiJwbmciLCJyZXNpemVfdG9fbGltaXQiOlszMDAsMzAwXX0sInB1ciI6InZhcmlhdGlvbiJ9fQ==--e1d036817a0840c585f202e70291f5cdd058753d/MoMo%20Logo.png" 
                                         alt="MoMo" style="height: 20px;" class="opacity-75">
                                    <i class="bi bi-credit-card text-muted"></i>
                                    <i class="bi bi-bank text-muted"></i>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Security Notice -->
                        <div class="mt-3 p-3 bg-light rounded-3">
                            <div class="d-flex align-items-center text-muted small">
                                <i class="bi bi-shield-check me-2 text-success"></i>
                                Thông tin thanh toán được bảo mật an toàn
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Continue Shopping Suggestion -->
    <div class="mt-5">
        <div class="card border-0 shadow-sm bg-light">
            <div class="card-body text-center py-4">
                <h5 class="fw-bold mb-3">Có thể bạn quan tâm</h5>
                <p class="text-muted mb-3">Khám phá thêm những sản phẩm tươi ngon khác</p>
                <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                    <i class="bi bi-eye me-2"></i>Xem thêm sản phẩm
                </a>
            </div>
        </div>
    </div>
@else
    <!-- Empty Cart State -->
    <div class="text-center py-5">
        <div class="mb-4">
            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center"
                 style="width: 120px; height: 120px;">
                <i class="bi bi-cart-x display-4 text-muted"></i>
            </div>
        </div>
        <h3 class="fw-bold mb-3">Giỏ hàng của bạn đang trống</h3>
        <p class="text-muted mb-4 fs-5">
            Hãy khám phá các sản phẩm tươi ngon và thêm vào giỏ hàng của bạn.
        </p>
        <div class="d-flex justify-content-center gap-3">
            <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                <i class="bi bi-bag me-2"></i>Xem sản phẩm
            </a>
            <a href="{{ route('categories.index') }}" class="btn btn-outline-primary btn-lg">
                <i class="bi bi-grid-3x3-gap me-2"></i>Danh mục sản phẩm
            </a>
        </div>
    </div>
@endif

<style>
.cart-item {
    transition: background-color 0.2s ease;
}

.cart-item:hover {
    background-color: rgba(0,0,0,0.02);
}

.quantity-btn {
    width: 32px;
    height: 32px;
    padding: 0;
    display: flex;
    align-items: center;
    justify-content: center;
}

.quantity-input {
    width: 60px;
    border-left: 0;
    border-right: 0;
}

.quantity-input:focus {
    box-shadow: none;
    border-color: #dee2e6;
}

.sticky-top {
    z-index: 10;
}

@media (max-width: 768px) {
    .sticky-top {
        position: relative !important;
        top: auto !important;
    }
}
</style>

<script>
function increaseQuantity(btn, maxStock) {
    const input = btn.parentElement.querySelector('.quantity-input');
    const currentValue = parseInt(input.value);
    if (currentValue < maxStock) {
        input.value = currentValue + 1;
    }
}

function decreaseQuantity(btn) {
    const input = btn.parentElement.querySelector('.quantity-input');
    const currentValue = parseInt(input.value);
    if (currentValue > 1) {
        input.value = currentValue - 1;
    }
}
</script>
@endsection
