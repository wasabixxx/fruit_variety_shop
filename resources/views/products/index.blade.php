@extends('layouts.app')

@section('title', 'Sản phẩm - Fruit Variety Shop')

@section('content')
<!-- Header Section -->
<div class="mb-5">
    <div class="position-relative overflow-hidden rounded-3">
        <div class="position-absolute w-100 h-100" 
             style="background: linear-gradient(135deg, rgba(255, 107, 53, 0.9) 0%, rgba(229, 87, 34, 0.8) 100%);"></div>
        
        <div class="position-relative text-white p-5">
            <div class="row align-items-center">
                <div class="col-md-8">
                    <div class="d-flex align-items-center mb-3">
                        <div class="bg-white bg-opacity-20 rounded-circle p-3 me-3">
                            <i class="bi bi-bag fs-3"></i>
                        </div>
                        <div>
                            <h1 class="display-5 fw-bold mb-0">Tất cả sản phẩm</h1>
                            <p class="fs-5 mb-0 opacity-90">Khám phá bộ sưu tập trái cây tươi ngon</p>
                        </div>
                    </div>
                    <div class="d-flex flex-wrap gap-2">
                        <span class="badge bg-white text-primary px-3 py-2 rounded-pill fw-semibold">
                            <i class="bi bi-box me-1"></i>{{ $products->total() }} sản phẩm
                        </span>
                        <span class="badge bg-white bg-opacity-20 px-3 py-2 rounded-pill">
                            <i class="bi bi-star-fill me-1"></i>Chất lượng cao
                        </span>
                        <span class="badge bg-white bg-opacity-20 px-3 py-2 rounded-pill">
                            <i class="bi bi-truck me-1"></i>Giao hàng nhanh
                        </span>
                    </div>
                </div>
                <div class="col-md-4 text-center">
                    <div class="d-inline-flex align-items-center justify-content-center bg-white bg-opacity-10 rounded-circle"
                         style="width: 120px; height: 120px;">
                        <i class="bi bi-basket display-4 opacity-75"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filter Section -->
@if($categories->count() > 0)
<div class="card border-0 shadow-sm mb-5">
    <div class="card-body p-4">
        <div class="d-flex align-items-center justify-content-between mb-4">
            <div class="d-flex align-items-center">
                <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                    <i class="bi bi-funnel text-primary"></i>
                </div>
                <h5 class="fw-bold mb-0">Lọc theo danh mục</h5>
            </div>
            <div class="text-muted">
                <small><i class="bi bi-info-circle me-1"></i>Chọn danh mục để xem sản phẩm cụ thể</small>
            </div>
        </div>
        
        <div class="d-flex flex-wrap gap-2">
            <a href="{{ route('products.index') }}" 
               class="btn btn-primary {{ !request('category') ? 'active' : 'btn-outline-primary' }} rounded-pill">
                <i class="bi bi-grid-3x3-gap me-1"></i>Tất cả 
                <span class="badge bg-white text-primary ms-2">{{ $products->total() }}</span>
            </a>
            @foreach($categories as $category)
                <a href="{{ route('categories.show', $category) }}" 
                   class="btn btn-outline-primary rounded-pill">
                    {{ $category->name }}
                    <span class="badge bg-primary text-white ms-2">{{ $category->products_count ?? 0 }}</span>
                </a>
            @endforeach
        </div>
    </div>
</div>
@endif

<!-- Products Grid -->
@if($products->count() > 0)
    <div class="row g-4">
        @foreach($products as $product)
        <div class="col-lg-3 col-md-4 col-sm-6">
            <div class="card product-card h-100 border-0 shadow-sm position-relative overflow-hidden">
                <!-- Product Image -->
                <div class="position-relative overflow-hidden">
                    @if($product->hasImage())
                        <img src="{{ $product->image_url }}" 
                             class="card-img-top object-fit-cover" 
                             alt="{{ $product->name }}"
                             style="height: 220px; transition: transform 0.3s ease;">
                    @else
                        <div class="card-img-top bg-gradient d-flex align-items-center justify-content-center" 
                             style="height: 220px; background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
                            <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                        </div>
                    @endif
                    
                    <!-- Stock Badge -->
                    <div class="position-absolute top-0 start-0 m-3">
                        @if($product->stock > 10)
                            <span class="badge bg-success px-2 py-1 rounded-pill">
                                <i class="bi bi-check-circle me-1"></i>Còn hàng
                            </span>
                        @elseif($product->stock > 0)
                            <span class="badge bg-warning px-2 py-1 rounded-pill">
                                <i class="bi bi-exclamation-circle me-1"></i>Sắp hết
                            </span>
                        @else
                            <span class="badge bg-danger px-2 py-1 rounded-pill">
                                <i class="bi bi-x-circle me-1"></i>Hết hàng
                            </span>
                        @endif
                    </div>
                    
                    <!-- Action Buttons Top Right -->
                    <div class="position-absolute top-0 end-0 m-3 d-flex flex-column gap-2">
                        <!-- Wishlist Button -->
                        @auth
                            <button class="btn btn-white btn-sm rounded-circle shadow-sm opacity-75 hover-opacity-100 wishlist-btn"
                                    data-product-id="{{ $product->id }}" 
                                    title="Thêm vào yêu thích">
                                <i class="bi bi-heart text-danger"></i>
                            </button>
                        @endauth
                        
                        <!-- Quick View Button -->
                        <a href="{{ route('products.show', $product) }}" 
                           class="btn btn-white btn-sm rounded-circle shadow-sm opacity-75 hover-opacity-100"
                           title="Xem chi tiết">
                            <i class="bi bi-eye"></i>
                        </a>
                    </div>
                </div>
                
                <!-- Product Info -->
                <div class="card-body d-flex flex-column p-4">
                    <!-- Category Badge -->
                    <div class="mb-2">
                        <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1 rounded-pill small">
                            {{ $product->category->name ?? 'Chưa phân loại' }}
                        </span>
                    </div>
                    
                    <!-- Product Name -->
                    <h5 class="card-title fw-bold mb-2 lh-sm">{{ $product->name }}</h5>
                    
                    <!-- Product Description -->
                    @if($product->description)
                        <p class="card-text text-muted mb-3 flex-grow-1 small">
                            {{ Str::limit($product->description, 80) }}
                        </p>
                    @endif
                    
                    <!-- Price and Stock Info -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="price-info">
                            <span class="h5 fw-bold text-primary mb-0">
                                {{ number_format($product->price, 0, ',', '.') }}đ
                            </span>
                            <small class="text-muted d-block">/ gói</small>
                        </div>
                        <div class="text-end">
                            <small class="text-muted d-block">Còn lại</small>
                            <span class="fw-semibold">{{ $product->stock ?? 0 }}</span>
                        </div>
                    </div>
                    
                    <!-- Action Buttons -->
                    <div class="mt-auto d-flex gap-2">
                        @if($product->stock > 0)
                            <form method="POST" action="{{ route('cart.add', $product) }}" class="flex-grow-1">
                                @csrf
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-primary w-100 btn-sm">
                                    <i class="bi bi-cart-plus me-1"></i>Thêm vào giỏ
                                </button>
                            </form>
                        @else
                            <button class="btn btn-secondary w-100 btn-sm" disabled>
                                <i class="bi bi-x-circle me-1"></i>Hết hàng
                            </button>
                        @endif
                        <a href="{{ route('products.show', $product) }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-eye"></i>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <!-- Pagination -->
    @if($products->hasPages())
        <div class="d-flex justify-content-center mt-5">
            <div class="pagination-wrapper">
                {{ $products->links('pagination::bootstrap-4') }}
            </div>
        </div>
    @endif
@else
    <!-- Empty State -->
    <div class="text-center py-5">
        <div class="mb-4">
            <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center"
                 style="width: 120px; height: 120px;">
                <i class="bi bi-inbox display-4 text-muted"></i>
            </div>
        </div>
        <h3 class="fw-bold mb-3">Chưa có sản phẩm nào</h3>
        <p class="text-muted mb-4 fs-5">Hiện tại chưa có sản phẩm nào trong danh mục này. Hãy quay lại sau!</p>
        <div class="d-flex justify-content-center gap-3">
            <a href="/" class="btn btn-primary">
                <i class="bi bi-house me-2"></i>Về trang chủ
            </a>
            <a href="{{ route('categories.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-grid-3x3-gap me-2"></i>Xem danh mục
            </a>
        </div>
    </div>
@endif

<style>
.product-card {
    transition: all 0.3s ease;
}

.product-card:hover {
    transform: translateY(-8px);
    box-shadow: 0 12px 32px rgba(0,0,0,0.15) !important;
}

.product-card:hover img {
    transform: scale(1.05);
}

.btn-white {
    background: white;
    color: var(--dark);
    border: 1px solid rgba(0,0,0,0.1);
}

.btn-white:hover {
    background: white;
    color: var(--primary);
    border-color: var(--primary);
}

.hover-opacity-100:hover {
    opacity: 1 !important;
}

.pagination-wrapper .pagination {
    border-radius: var(--radius-lg);
    box-shadow: var(--shadow-sm);
}

.pagination-wrapper .page-link {
    border: none;
    color: var(--dark);
    font-weight: 500;
    padding: 0.75rem 1rem;
    margin: 0 0.25rem;
    border-radius: var(--radius-md);
}

.pagination-wrapper .page-item.active .page-link {
    background: var(--gradient-primary);
    border-color: transparent;
}

.pagination-wrapper .page-link:hover {
    background: rgba(255, 107, 53, 0.1);
    color: var(--primary);
}

@media (max-width: 768px) {
    .product-card:hover {
        transform: none;
    }
    
    .col-sm-6:nth-child(odd) .product-card {
        margin-bottom: 1rem;
    }
}

/* Wishlist button styles */
.wishlist-btn {
    transition: all 0.2s ease;
}

.wishlist-btn:hover {
    transform: scale(1.1);
}

.wishlist-btn.active i {
    color: #E74C3C !important;
}

.wishlist-btn.loading i {
    animation: spin 1s linear infinite;
}

@keyframes spin {
    from { transform: rotate(0deg); }
    to { transform: rotate(360deg); }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize wishlist status for all products
    initializeWishlistStatus();
    
    // Handle wishlist button clicks
    document.querySelectorAll('.wishlist-btn').forEach(button => {
        button.addEventListener('click', function() {
            toggleWishlist(this);
        });
    });
    
    function initializeWishlistStatus() {
        const wishlistButtons = document.querySelectorAll('.wishlist-btn');
        
        wishlistButtons.forEach(button => {
            const productId = button.dataset.productId;
            
            // Check if product is in wishlist
            fetch('/wishlist/check', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ product_id: productId })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.in_wishlist) {
                    button.classList.add('active');
                    button.querySelector('i').classList.remove('bi-heart');
                    button.querySelector('i').classList.add('bi-heart-fill');
                    button.title = 'Xóa khỏi yêu thích';
                }
            })
            .catch(error => {
                console.error('Error checking wishlist status:', error);
            });
        });
    }
    
    function toggleWishlist(button) {
        const productId = button.dataset.productId;
        const icon = button.querySelector('i');
        const isActive = button.classList.contains('active');
        
        // Show loading state
        button.classList.add('loading');
        button.disabled = true;
        
        // Send toggle request
        fetch('/wishlist/toggle', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                'Accept': 'application/json'
            },
            body: JSON.stringify({ product_id: productId })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update button state
                if (data.action === 'added') {
                    button.classList.add('active');
                    icon.classList.remove('bi-heart');
                    icon.classList.add('bi-heart-fill');
                    button.title = 'Xóa khỏi yêu thích';
                } else {
                    button.classList.remove('active');
                    icon.classList.remove('bi-heart-fill');
                    icon.classList.add('bi-heart');
                    button.title = 'Thêm vào yêu thích';
                }
                
                // Update wishlist count in navbar
                if (window.updateWishlistCount) {
                    window.updateWishlistCount(data.wishlist_count);
                }
                
                // Show success message
                showToast(data.message, 'success');
            } else {
                throw new Error(data.message || 'Có lỗi xảy ra');
            }
        })
        .catch(error => {
            console.error('Error toggling wishlist:', error);
            showToast('Có lỗi xảy ra: ' + error.message, 'error');
        })
        .finally(() => {
            // Remove loading state
            button.classList.remove('loading');
            button.disabled = false;
        });
    }
    
    function showToast(message, type) {
        // Create toast element
        const toast = document.createElement('div');
        toast.className = `alert alert-${type === 'success' ? 'success' : 'danger'} position-fixed`;
        toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
        toast.innerHTML = `
            ${message}
            <button type="button" class="btn-close ms-2" onclick="this.parentElement.remove()"></button>
        `;
        
        document.body.appendChild(toast);
        
        // Auto remove after 3 seconds
        setTimeout(() => {
            if (toast.parentElement) {
                toast.remove();
            }
        }, 3000);
    }
});
</script>
@endsection