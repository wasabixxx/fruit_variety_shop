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
                        <div class="row align-items-center g-3">
                            <!-- Product Image -->
                            <div class="col-auto">
                                @if($item['image'])
                                    <img src="{{ $item['image'] }}" 
                                         alt="{{ $item['name'] }}" 
                                         class="img-fluid rounded-3 shadow-sm"
                                         style="height: 80px; width: 80px; object-fit: cover;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center rounded-3" 
                                         style="height: 80px; width: 80px;">
                                        <i class="bi bi-image text-muted fs-3"></i>
                                    </div>
                                @endif
                            </div>
                            
                            <!-- Product Info -->
                            <div class="col">
                                <h6 class="fw-bold mb-1">{{ $item['name'] }}</h6>
                                <div class="d-flex align-items-center text-muted small">
                                    <i class="bi bi-check-circle me-1"></i>
                                    @if($item['stock'] > 0)
                                        Còn {{ $item['stock'] }} sản phẩm
                                    @else
                                        <span class="text-danger">Hết hàng</span>
                                    @endif
                                </div>
                                <!-- Price on mobile -->
                                <div class="d-md-none mt-1">
                                    <span class="fw-bold text-primary">
                                        {{ number_format($item['price'], 0, ',', '.') }}đ
                                    </span>
                                    <small class="text-muted">/ gói</small>
                                </div>
                            </div>
                            
                            <!-- Price (Desktop only) -->
                            <div class="col-auto d-none d-md-block text-end">
                                <div class="fw-bold text-primary">
                                    {{ number_format($item['price'], 0, ',', '.') }}đ
                                </div>
                                <small class="text-muted">/ gói</small>
                            </div>
                            
                            <!-- Quantity Controls -->
                            <div class="col-auto">
                                <form method="POST" action="{{ route('cart.update', $item['id']) }}">
                                    @csrf
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="input-group" style="width: 150px;">
                                            <button type="button" class="btn btn-outline-secondary btn-sm quantity-btn" 
                                                    onclick="decreaseQuantity(this)">
                                                <i class="bi bi-dash"></i>
                                            </button>
                                            <input type="number" 
                                                   name="quantity" 
                                                   value="{{ $item['quantity'] }}" 
                                                   min="1" 
                                                   max="{{ $item['stock'] }}" 
                                                   class="form-control form-control-sm text-center quantity-input"
                                                   data-quantity="{{ $item['quantity'] }}"
                                                   autocomplete="off"
                                                   style="background-color: white !important; color: #000 !important; font-weight: 500;">
                                            <button type="button" class="btn btn-outline-secondary btn-sm quantity-btn" 
                                                    onclick="increaseQuantity(this, {{ $item['stock'] }})">
                                                <i class="bi bi-plus"></i>
                                            </button>
                                        </div>
                                        <button type="submit" class="btn btn-primary btn-sm">
                                            <i class="bi bi-arrow-clockwise"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            
                            <!-- Subtotal & Remove -->
                            <div class="col-auto text-end">
                                <div class="fw-bold text-dark mb-2 text-nowrap">
                                    {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}đ
                                </div>
                                <form method="POST" action="{{ route('cart.remove.product', $item['id']) }}">
                                    @csrf
                                    <button type="submit" class="btn btn-outline-danger btn-sm" 
                                            onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">
                                        <i class="bi bi-x-lg"></i>
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
                        
                        <!-- Voucher Section -->
                        <div class="mb-4">
                            <h6 class="fw-bold mb-3">
                                <i class="bi bi-ticket-perforated me-2"></i>Mã giảm giá
                            </h6>
                            
                            <!-- Voucher Input -->
                            <form id="voucherForm" class="mb-3">
                                @csrf
                                <div class="input-group">
                                    <input type="text" class="form-control" id="voucherCode" 
                                           placeholder="Nhập mã giảm giá" maxlength="50">
                                    <button class="btn btn-outline-primary" type="submit" id="applyVoucherBtn">
                                        <i class="bi bi-check-circle me-1"></i>Áp dụng
                                    </button>
                                </div>
                            </form>
                            
                            <!-- Applied Voucher Display -->
                            <div id="appliedVoucher" style="display: none;">
                                <div class="alert alert-success d-flex align-items-center justify-content-between p-2 mb-0">
                                    <div class="d-flex align-items-center">
                                        <i class="bi bi-check-circle-fill me-2"></i>
                                        <div>
                                            <small class="fw-bold d-block" id="appliedVoucherName"></small>
                                            <small class="text-muted" id="appliedVoucherDiscount"></small>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-danger" id="removeVoucherBtn">
                                        <i class="bi bi-x"></i>
                                    </button>
                                </div>
                            </div>
                            
                            <!-- Voucher Error -->
                            <div id="voucherError" class="alert alert-danger p-2 mt-2" style="display: none;">
                                <small><i class="bi bi-exclamation-circle me-1"></i><span id="voucherErrorText"></span></small>
                            </div>
                            
                            <!-- Available Vouchers -->
                            <div class="mt-3">
                                <small class="text-muted d-block mb-2">Voucher có sẵn:</small>
                                <div id="availableVouchers" class="d-flex flex-wrap gap-1">
                                    <!-- Vouchers sẽ được load qua AJAX -->
                                </div>
                            </div>
                        </div>
                        
                        <!-- Pricing Breakdown -->
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Tạm tính:</span>
                            <span class="fw-semibold" id="subtotal">{{ number_format($total, 0, ',', '.') }}đ</span>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mb-2" id="discountRow" style="display: none;">
                            <span class="text-muted">Giảm giá:</span>
                            <span class="fw-semibold text-success" id="discountAmount">-0đ</span>
                        </div>
                        
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="text-muted">Phí vận chuyển:</span>
                            <span class="fw-semibold text-success">Miễn phí</span>
                        </div>
                        
                        <hr>
                        
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <span class="fw-bold fs-5">Tổng cộng:</span>
                            <span class="fw-bold fs-4 text-primary" id="finalTotal">{{ number_format($total, 0, ',', '.') }}đ</span>
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
    background-color: #f8f9fa;
}

.voucher-badge {
    cursor: pointer;
    transition: all 0.2s ease;
}

.voucher-badge:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}
</style>

@push('scripts')
<script>
let currentVoucher = @json($appliedVoucher ?? null);
let cartSubtotal = {{ $total }};
let currentDiscountAmount = {{ $discountAmount ?? 0 }};

document.addEventListener('DOMContentLoaded', function() {
    loadAvailableVouchers();
    
    // Show applied voucher if exists
    if (currentVoucher && currentDiscountAmount > 0) {
        showAppliedVoucher(currentVoucher, currentDiscountAmount);
        updateCartTotals(currentDiscountAmount);
    }
    
    // Ensure all quantity inputs show their values
    document.querySelectorAll('.quantity-input').forEach(function(input) {
        const value = input.getAttribute('value') || input.getAttribute('data-quantity');
        if (value) {
            input.value = value;
            input.setAttribute('value', value);
            // Force re-render
            input.style.display = 'none';
            input.offsetHeight; // trigger reflow
            input.style.display = 'block';
        }
    });
    
    // Voucher form submission
    document.getElementById('voucherForm').addEventListener('submit', function(e) {
        e.preventDefault();
        applyVoucher();
    });
    
    // Remove voucher
    document.getElementById('removeVoucherBtn').addEventListener('click', function() {
        removeVoucher();
    });
});

// Load available vouchers
function loadAvailableVouchers() {
    fetch('{{ route("vouchers.available") }}')
    .then(response => response.json())
    .then(data => {
        const container = document.getElementById('availableVouchers');
        container.innerHTML = '';
        
        if (data.vouchers && data.vouchers.length > 0) {
            data.vouchers.forEach(voucher => {
                const badge = document.createElement('span');
                badge.className = 'badge bg-primary voucher-badge me-1 mb-1';
                badge.style.cursor = 'pointer';
                badge.innerHTML = `<i class="bi bi-tag me-1"></i>${voucher.code}`;
                badge.title = `${voucher.name} - Giảm ${voucher.discount_text}`;
                badge.onclick = () => {
                    document.getElementById('voucherCode').value = voucher.code;
                    applyVoucher();
                };
                container.appendChild(badge);
            });
        } else {
            container.innerHTML = '<small class="text-muted">Không có voucher nào khả dụng</small>';
        }
    })
    .catch(error => {
        console.error('Error loading vouchers:', error);
    });
}

// Apply voucher
function applyVoucher() {
    const code = document.getElementById('voucherCode').value.trim();
    if (!code) return;
    
    const btn = document.getElementById('applyVoucherBtn');
    const originalText = btn.innerHTML;
    btn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Đang xử lý...';
    btn.disabled = true;
    
    // Hide previous errors
    document.getElementById('voucherError').style.display = 'none';
    
    fetch('{{ route("vouchers.cart.apply") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({
            voucher_code: code,
            cart_total: cartSubtotal
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            currentVoucher = data.voucher;
            showAppliedVoucher(data.voucher, data.discount_amount);
            updateCartTotals(data.discount_amount);
            document.getElementById('voucherCode').value = '';
        } else {
            showVoucherError(data.message);
        }
    })
    .catch(error => {
        console.error('Error applying voucher:', error);
        showVoucherError('Có lỗi xảy ra khi áp dụng voucher');
    })
    .finally(() => {
        btn.innerHTML = originalText;
        btn.disabled = false;
    });
}

// Remove voucher
function removeVoucher() {
    fetch('{{ route("vouchers.cart.remove") }}', {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            currentVoucher = null;
            hideAppliedVoucher();
            updateCartTotals(0);
        }
    })
    .catch(error => {
        console.error('Error removing voucher:', error);
    });
}

// Show applied voucher
function showAppliedVoucher(voucher, discountAmount) {
    document.getElementById('appliedVoucherName').textContent = voucher.name;
    document.getElementById('appliedVoucherDiscount').textContent = `Giảm ${formatCurrency(discountAmount)}`;
    document.getElementById('appliedVoucher').style.display = 'block';
}

// Hide applied voucher
function hideAppliedVoucher() {
    document.getElementById('appliedVoucher').style.display = 'none';
}

// Show voucher error
function showVoucherError(message) {
    document.getElementById('voucherErrorText').textContent = message;
    document.getElementById('voucherError').style.display = 'block';
}

// Update cart totals
function updateCartTotals(discountAmount) {
    const discountRow = document.getElementById('discountRow');
    const discountAmountEl = document.getElementById('discountAmount');
    const finalTotal = document.getElementById('finalTotal');
    
    if (discountAmount > 0) {
        discountRow.style.display = 'flex';
        discountAmountEl.textContent = '-' + formatCurrency(discountAmount);
        finalTotal.textContent = formatCurrency(cartSubtotal - discountAmount);
    } else {
        discountRow.style.display = 'none';
        finalTotal.textContent = formatCurrency(cartSubtotal);
    }
}

// Format currency
function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN').format(amount) + 'đ';
}

// Quantity functions
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
@endpush

@push('styles')
<style>
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
    border: 1px solid #dee2e6;
}

.quantity-input {
    width: 80px;
    border-left: 0;
    border-right: 0;
    text-align: center;
    -webkit-appearance: textfield;
    -moz-appearance: textfield;
    appearance: textfield;
    background-color: white !important;
    color: #000 !important;
    font-weight: 500;
    min-width: 80px;
    padding: 0.375rem 0.5rem;
    font-size: 0.9rem;
}

.quantity-input::-webkit-outer-spin-button,
.quantity-input::-webkit-inner-spin-button {
    -webkit-appearance: none;
    margin: 0;
}

.quantity-input:focus {
    box-shadow: none;
    border-color: #dee2e6;
    background-color: white !important;
    color: #000 !important;
}

.sticky-top {
    z-index: 10;
}

.cart-item {
    transition: background-color 0.2s ease;
}

.cart-item .row {
    min-height: 100px;
}

/* Ensure proper spacing on mobile */
@media (max-width: 768px) {
    .sticky-top {
        position: relative !important;
        top: auto !important;
    }
    
    .cart-item .row {
        min-height: auto;
    }
    
    .cart-item {
        padding: 1rem !important;
    }
    
    .quantity-btn {
        width: 28px;
        height: 28px;
    }
    
    .quantity-input {
        width: 70px;
        font-size: 0.875rem;
        min-width: 70px;
    }
}
</style>
@endpush

@endsection
