@extends('layouts.app')

@section('title', 'Voucher Giảm Giá - Fruit Variety Shop')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="mb-5">
        <div class="text-center">
            <h1 class="h2 fw-bold mb-3">
                <i class="bi bi-gift text-primary me-2"></i>Voucher Giảm Giá
            </h1>
            <p class="text-muted fs-5 mb-0">Khám phá các ưu đãi hấp dẫn dành riêng cho bạn</p>
        </div>
    </div>

    @if(auth()->check())
        <!-- User Actions -->
        <div class="row mb-4">
            <div class="col-md-6 offset-md-3">
                <div class="card border-0 shadow-sm bg-primary bg-opacity-10">
                    <div class="card-body text-center">
                        <h5 class="fw-bold mb-3">
                            <i class="bi bi-person-check text-primary me-2"></i>Chào {{ auth()->user()->name }}!
                        </h5>
                        <p class="text-muted mb-3">Xem lịch sử sử dụng voucher của bạn</p>
                        <a href="{{ route('vouchers.my-vouchers') }}" class="btn btn-primary">
                            <i class="bi bi-clock-history me-2"></i>Lịch sử voucher
                        </a>
                    </div>
                </div>
            </div>
        </div>
    @endif

    @if($vouchers->count() > 0)
        <!-- Vouchers Grid -->
        <div class="row">
            @foreach($vouchers as $voucher)
            <div class="col-lg-4 col-md-6 mb-4">
                <div class="card border-0 shadow-sm h-100 voucher-card">
                    <div class="card-header bg-transparent border-bottom-0 pb-0">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="badge bg-{{ $voucher->getStatusColor() }}-subtle text-{{ $voucher->getStatusColor() }}">
                                {{ $voucher->getStatusText() }}
                            </div>
                            @if($voucher->type === 'percentage')
                                <div class="bg-success bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                                     style="width: 40px; height: 40px;">
                                    <i class="bi bi-percent text-success"></i>
                                </div>
                            @else
                                <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center"
                                     style="width: 40px; height: 40px;">
                                    <i class="bi bi-currency-exchange text-primary"></i>
                                </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="card-body">
                        <h5 class="fw-bold mb-2">{{ $voucher->name }}</h5>
                        <p class="text-muted mb-3">{{ Str::limit($voucher->description, 80) }}</p>
                        
                        <div class="row g-3 mb-3">
                            <div class="col-6">
                                <div class="text-center p-2 bg-light rounded">
                                    <div class="fw-bold text-primary">{{ $voucher->getDiscountText() }}</div>
                                    <small class="text-muted">Giảm giá</small>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="text-center p-2 bg-light rounded">
                                    <div class="fw-bold text-info">
                                        @if($voucher->minimum_order_amount)
                                            {{ number_format($voucher->minimum_order_amount, 0, ',', '.') }}đ
                                        @else
                                            Không
                                        @endif
                                    </div>
                                    <small class="text-muted">Đơn tối thiểu</small>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <span class="small text-muted">Mã voucher:</span>
                                <code class="bg-light px-2 py-1 rounded">{{ $voucher->code }}</code>
                            </div>
                            
                            @if($voucher->usage_limit)
                            <div class="mb-2">
                                <div class="d-flex justify-content-between align-items-center small mb-1">
                                    <span class="text-muted">Đã sử dụng:</span>
                                    <span>{{ $voucher->used_count }}/{{ $voucher->usage_limit }}</span>
                                </div>
                                <div class="progress" style="height: 6px;">
                                    <div class="progress-bar" 
                                         style="width: {{ ($voucher->used_count / $voucher->usage_limit) * 100 }}%"></div>
                                </div>
                            </div>
                            @endif
                            
                            @if($voucher->expires_at)
                            <div class="d-flex justify-content-between align-items-center small">
                                <span class="text-muted">Hết hạn:</span>
                                <span class="text-{{ $voucher->expires_at->isPast() ? 'danger' : 'success' }}">
                                    {{ $voucher->expires_at->format('d/m/Y H:i') }}
                                </span>
                            </div>
                            @endif
                        </div>
                    </div>
                    
                    <div class="card-footer bg-transparent border-top-0">
                        @if($voucher->isValid())
                            @auth
                                <button class="btn btn-primary w-100 copy-voucher-btn" 
                                        data-code="{{ $voucher->code }}"
                                        data-name="{{ $voucher->name }}">
                                    <i class="bi bi-clipboard me-2"></i>Sao chép mã
                                </button>
                            @else
                                <a href="{{ route('login') }}" class="btn btn-outline-primary w-100">
                                    <i class="bi bi-box-arrow-in-right me-2"></i>Đăng nhập để sử dụng
                                </a>
                            @endauth
                        @else
                            <button class="btn btn-secondary w-100" disabled>
                                <i class="bi bi-x-circle me-2"></i>{{ $voucher->getStatusText() }}
                            </button>
                        @endif
                    </div>
                </div>
            </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($vouchers->hasPages())
        <div class="d-flex justify-content-center mt-4">
            {{ $vouchers->links() }}
        </div>
        @endif
    @else
        <!-- Empty State -->
        <div class="text-center py-5">
            <div class="mb-4">
                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center"
                     style="width: 120px; height: 120px;">
                    <i class="bi bi-gift display-4 text-muted"></i>
                </div>
            </div>
            <h3 class="fw-bold mb-3">Hiện tại chưa có voucher nào</h3>
            <p class="text-muted mb-4 fs-5">
                Các voucher giảm giá sẽ sớm có mặt. Hãy quay lại sau nhé!
            </p>
            <a href="{{ route('products.index') }}" class="btn btn-primary btn-lg">
                <i class="bi bi-bag me-2"></i>Mua sắm ngay
            </a>
        </div>
    @endif
</div>

<!-- Toast for copy feedback -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="copyToast" class="toast" role="alert">
        <div class="toast-header">
            <i class="bi bi-check-circle-fill text-success me-2"></i>
            <strong class="me-auto">Thành công</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body" id="toastMessage">
            Đã sao chép mã voucher!
        </div>
    </div>
</div>

<style>
.voucher-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.voucher-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}

.progress {
    background-color: #e9ecef;
}

.progress-bar {
    background: linear-gradient(45deg, #007bff, #0056b3);
}

code {
    font-size: 0.875rem;
    color: #495057;
}

.badge {
    font-size: 0.75rem;
}

.copy-voucher-btn {
    transition: all 0.2s ease;
}

.copy-voucher-btn:hover {
    transform: scale(1.02);
}
</style>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Copy voucher code functionality
    document.querySelectorAll('.copy-voucher-btn').forEach(button => {
        button.addEventListener('click', function() {
            const code = this.getAttribute('data-code');
            const name = this.getAttribute('data-name');
            
            // Copy to clipboard
            navigator.clipboard.writeText(code).then(() => {
                // Show success toast
                const toast = document.getElementById('copyToast');
                const toastMessage = document.getElementById('toastMessage');
                toastMessage.textContent = `Đã sao chép mã "${code}" của ${name}!`;
                
                const bsToast = new bootstrap.Toast(toast);
                bsToast.show();
                
                // Update button temporarily
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="bi bi-check-circle me-2"></i>Đã sao chép!';
                this.classList.add('btn-success');
                this.classList.remove('btn-primary');
                
                setTimeout(() => {
                    this.innerHTML = originalText;
                    this.classList.add('btn-primary');
                    this.classList.remove('btn-success');
                }, 2000);
            }).catch(() => {
                // Fallback for older browsers
                const textArea = document.createElement('textarea');
                textArea.value = code;
                document.body.appendChild(textArea);
                textArea.select();
                document.execCommand('copy');
                document.body.removeChild(textArea);
                
                const toast = document.getElementById('copyToast');
                const toastMessage = document.getElementById('toastMessage');
                toastMessage.textContent = `Đã sao chép mã "${code}"!`;
                
                const bsToast = new bootstrap.Toast(toast);
                bsToast.show();
            });
        });
    });
});
</script>
@endpush
@endsection