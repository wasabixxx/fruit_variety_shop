@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('products.index') }}">Sản phẩm</a></li>
            @if($product->category)
                <li class="breadcrumb-item"><a href="{{ route('categories.show', $product->category) }}">{{ $product->category->name }}</a></li>
            @endif
            <li class="breadcrumb-item active">{{ $product->name }}</li>
        </ol>
    </nav>

    <!-- Product Details -->
    <div class="row mb-5">
        <div class="col-lg-6 mb-4">
            <div class="product-image-container">
                @if($product->hasImage())
                    <img src="{{ $product->image_url }}" class="img-fluid rounded shadow" alt="{{ $product->name }}" style="width: 100%; max-height: 500px; object-fit: cover;">
                @else
                    <div class="bg-light rounded shadow d-flex align-items-center justify-content-center" style="height: 500px;">
                        <i class="bi bi-image text-muted" style="font-size: 5rem;"></i>
                    </div>
                @endif
            </div>
        </div>
        
        <div class="col-lg-6">
            <div class="product-info">
                <h1 class="display-5 mb-3">{{ $product->name }}</h1>
                
                @if($product->category)
                    <div class="mb-3">
                        <a href="{{ route('categories.show', $product->category) }}" class="badge bg-success text-decoration-none fs-6">
                            <i class="bi bi-tag"></i> {{ $product->category->name }}
                        </a>
                    </div>
                @endif
                
                <div class="mb-4">
                    <h2 class="text-success mb-0">{{ number_format($product->price) }} VNĐ</h2>
                </div>
                
                <div class="mb-4">
                    <div class="d-flex align-items-center">
                        <strong class="me-3">Tình trạng:</strong>
                        @if($product->stock > 10)
                            <span class="badge bg-success fs-6">
                                <i class="bi bi-check-circle"></i> Còn hàng ({{ $product->stock }} sản phẩm)
                            </span>
                        @elseif($product->stock > 0)
                            <span class="badge bg-warning fs-6">
                                <i class="bi bi-exclamation-triangle"></i> Sắp hết ({{ $product->stock }} sản phẩm)
                            </span>
                        @else
                            <span class="badge bg-danger fs-6">
                                <i class="bi bi-x-circle"></i> Hết hàng
                            </span>
                        @endif
                    </div>
                </div>
                
                @if($product->description)
                    <div class="mb-4">
                        <h5 class="mb-3">
                            <i class="bi bi-info-circle"></i> Mô tả sản phẩm
                        </h5>
                        <div class="border-start border-success border-3 ps-3">
                            <p class="text-muted mb-0">{{ $product->description }}</p>
                        </div>
                    </div>
                @endif
                
                <div class="d-flex flex-wrap gap-3">
                    @if($product->stock > 0)
                        <form method="POST" action="{{ route('cart.add', $product) }}" class="d-flex flex-grow-1 gap-2" style="max-width: 350px;">
                            @csrf
                            <input type="number" name="quantity" value="1" min="1" max="{{ $product->stock }}" class="form-control form-control-lg" style="width: 90px;">
                            <button type="submit" class="btn btn-success btn-lg flex-grow-1">
                                <i class="bi bi-cart-plus"></i> Thêm vào giỏ hàng
                            </button>
                        </form>
                        
                        @auth
                            <button class="btn btn-outline-danger btn-lg wishlist-btn" 
                                    data-product-id="{{ $product->id }}" 
                                    title="Thêm vào yêu thích">
                                <i class="bi bi-heart"></i> Yêu thích
                            </button>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-outline-danger btn-lg">
                                <i class="bi bi-heart"></i> Yêu thích
                            </a>
                        @endauth
                    @else
                        <button class="btn btn-secondary btn-lg flex-grow-1" disabled style="max-width: 250px;">
                            <i class="bi bi-x-circle"></i> Hết hàng
                        </button>
                    @endif
                    
                    <a href="{{ route('products.index') }}" class="btn btn-outline-primary btn-lg">
                        <i class="bi bi-arrow-left"></i> Quay lại
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Similar Products Section -->
    @if(isset($similarProducts) && $similarProducts->count() > 0)
    <div class="row mb-5">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-transparent border-0 py-4">
                    <div class="d-flex align-items-center justify-content-between">
                        <div>
                            <h4 class="mb-1">
                                <i class="bi bi-arrow-repeat text-primary me-2"></i>
                                Sản phẩm tương tự
                            </h4>
                            <p class="text-muted mb-0">Những sản phẩm khác mà bạn có thể quan tâm</p>
                        </div>
                        <a href="{{ route('recommendations.similar', $product) }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-arrow-right me-1"></i>Xem tất cả
                        </a>
                    </div>
                </div>
                <div class="card-body py-0">
                    <div class="row g-3" id="similarProductsContainer">
                        @foreach($similarProducts as $similarProduct)
                        <div class="col-lg-3 col-md-4 col-6">
                            <div class="card product-card h-100 border-0 shadow-sm position-relative overflow-hidden">
                                @if($similarProduct->hasImage())
                                    <div class="position-relative overflow-hidden">
                                        <img src="{{ $similarProduct->image_url }}" 
                                             class="card-img-top object-fit-cover" 
                                             alt="{{ $similarProduct->name }}"
                                             style="height: 180px; transition: transform 0.3s ease;">
                                        @if($similarProduct->category->id === $product->category->id)
                                        <div class="position-absolute top-0 start-0 m-2">
                                            <span class="badge bg-primary px-2 py-1 rounded-pill small">
                                                <i class="bi bi-check-circle-fill me-1"></i>Cùng loại
                                            </span>
                                        </div>
                                        @endif
                                        @auth
                                        <div class="position-absolute top-0 end-0 m-2">
                                            <button class="btn btn-sm btn-outline-light rounded-circle wishlist-btn" 
                                                    data-product-id="{{ $similarProduct->id }}"
                                                    title="Thêm vào danh sách yêu thích">
                                                <i class="bi bi-heart"></i>
                                            </button>
                                        </div>
                                        @endauth
                                    </div>
                                @else
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center position-relative" 
                                         style="height: 180px;">
                                        <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                                        @if($similarProduct->category->id === $product->category->id)
                                        <div class="position-absolute top-0 start-0 m-2">
                                            <span class="badge bg-primary px-2 py-1 rounded-pill small">
                                                <i class="bi bi-check-circle-fill me-1"></i>Cùng loại
                                            </span>
                                        </div>
                                        @endif
                                    </div>
                                @endif
                                
                                <div class="card-body p-3">
                                    <div class="mb-2">
                                        <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1 rounded-pill small">
                                            {{ $similarProduct->category->name }}
                                        </span>
                                    </div>
                                    
                                    <h6 class="card-title fw-bold mb-2">{{ Str::limit($similarProduct->name, 50) }}</h6>
                                    
                                    @if($similarProduct->average_rating > 0)
                                    <div class="mb-2">
                                        <div class="d-flex align-items-center">
                                            <div class="text-warning me-2">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= floor($similarProduct->average_rating))
                                                        <i class="bi bi-star-fill small"></i>
                                                    @elseif($i <= ceil($similarProduct->average_rating))
                                                        <i class="bi bi-star-half small"></i>
                                                    @else
                                                        <i class="bi bi-star small"></i>
                                                    @endif
                                                @endfor
                                            </div>
                                            <small class="text-muted">({{ $similarProduct->total_reviews ?? 0 }})</small>
                                        </div>
                                    </div>
                                    @endif
                                    
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="price-info">
                                            <span class="h6 fw-bold text-primary mb-0">
                                                {{ number_format($similarProduct->price, 0, ',', '.') }}đ
                                            </span>
                                            <small class="text-muted d-block">/ gói</small>
                                        </div>
                                        <div class="text-muted">
                                            @if($similarProduct->stock > 10)
                                                <span class="badge bg-success-subtle text-success small">Còn hàng</span>
                                            @elseif($similarProduct->stock > 0)
                                                <span class="badge bg-warning-subtle text-warning small">Sắp hết</span>
                                            @else
                                                <span class="badge bg-danger-subtle text-danger small">Hết hàng</span>
                                            @endif
                                        </div>
                                    </div>
                                    
                                    <div class="d-grid gap-2">
                                        <a href="{{ route('products.show', $similarProduct) }}" 
                                           class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-eye me-1"></i>Xem chi tiết
                                        </a>
                                        @if($similarProduct->stock > 0)
                                        <form action="{{ route('cart.add', $similarProduct) }}" method="POST" class="d-inline">
                                            @csrf
                                            <input type="hidden" name="quantity" value="1">
                                            <button type="submit" class="btn btn-primary btn-sm w-100">
                                                <i class="bi bi-cart-plus me-1"></i>Thêm vào giỏ
                                            </button>
                                        </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="card-footer bg-transparent border-0 text-center py-3">
                    <button class="btn btn-outline-secondary btn-sm" id="loadMoreSimilar" data-product-id="{{ $product->id }}" data-offset="8">
                        <i class="bi bi-arrow-clockwise me-2"></i>Xem thêm sản phẩm tương tự
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Product Reviews -->
    @include('products.partials.reviews')

    <!-- Related Products -->
    @if($relatedProducts->count() > 0)
    <div class="row">
        <div class="col-12">
            <div class="border-top pt-5">
                <h4 class="mb-4">
                    <i class="bi bi-grid"></i> Sản phẩm cùng danh mục
                </h4>
                <div class="row">
                    @foreach($relatedProducts as $related)
                        <div class="col-md-6 col-lg-3 mb-4">
                            <div class="card h-100 shadow-sm related-product-card">
                                @if($related->hasImage())
                                    <img src="{{ $related->image_url }}" class="card-img-top" alt="{{ $related->name }}" style="height: 200px; object-fit: cover;">
                                @else
                                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                        <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                                    </div>
                                @endif
                                <div class="card-body d-flex flex-column">
                                    <h6 class="card-title">{{ Str::limit($related->name, 40) }}</h6>
                                    <div class="mb-2">
                                        <span class="text-success fw-bold">{{ number_format($related->price) }} VNĐ</span>
                                    </div>
                                    <div class="mb-3">
                                        @if($related->stock > 0)
                                            <small class="text-success">
                                                <i class="bi bi-check-circle"></i> Còn hàng
                                            </small>
                                        @else
                                            <small class="text-danger">
                                                <i class="bi bi-x-circle"></i> Hết hàng
                                            </small>
                                        @endif
                                    </div>
                                    <div class="mt-auto">
                                        <a href="{{ route('products.show', $related) }}" class="btn btn-outline-success w-100">
                                            <i class="bi bi-eye"></i> Xem chi tiết
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    @endif
</div>

<style>
.related-product-card {
    transition: transform 0.3s ease;
    border: none;
}

.related-product-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 6px 20px rgba(0,0,0,0.15) !important;
}

.product-image-container img {
    transition: transform 0.3s ease;
}

.product-image-container:hover img {
    transform: scale(1.02);
}

/* Wishlist button styles */
.wishlist-btn {
    transition: all 0.2s ease;
}

.wishlist-btn:hover {
    transform: scale(1.02);
}

.wishlist-btn.active {
    background-color: #E74C3C !important;
    border-color: #E74C3C !important;
    color: white !important;
}

.wishlist-btn.active i {
    color: white !important;
}

.wishlist-btn.loading {
    opacity: 0.7;
    pointer-events: none;
}

.wishlist-btn.loading i {
    animation: pulse 1s infinite;
}

@keyframes pulse {
    0% { opacity: 1; }
    50% { opacity: 0.5; }
    100% { opacity: 1; }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const wishlistBtn = document.querySelector('.wishlist-btn');
    
    if (wishlistBtn) {
        // Initialize wishlist status
        checkWishlistStatus();
        
        // Handle wishlist button click
        wishlistBtn.addEventListener('click', function() {
            toggleWishlist();
        });
    }
    
    function checkWishlistStatus() {
        const productId = wishlistBtn.dataset.productId;
        
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
                updateButtonState(true);
            }
        })
        .catch(error => {
            console.error('Error checking wishlist status:', error);
        });
    }
    
    function toggleWishlist() {
        const productId = wishlistBtn.dataset.productId;
        
        // Show loading state
        wishlistBtn.classList.add('loading');
        wishlistBtn.disabled = true;
        
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
                updateButtonState(data.action === 'added');
                
                // Update wishlist count in navbar
                if (window.updateWishlistCount) {
                    window.updateWishlistCount(data.wishlist_count);
                }
                
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
            wishlistBtn.classList.remove('loading');
            wishlistBtn.disabled = false;
        });
    }
    
    function updateButtonState(inWishlist) {
        const icon = wishlistBtn.querySelector('i');
        
        if (inWishlist) {
            wishlistBtn.classList.add('active');
            icon.classList.remove('bi-heart');
            icon.classList.add('bi-heart-fill');
            wishlistBtn.innerHTML = '<i class="bi bi-heart-fill"></i> Đã yêu thích';
            wishlistBtn.title = 'Xóa khỏi yêu thích';
        } else {
            wishlistBtn.classList.remove('active');
            icon.classList.remove('bi-heart-fill');
            icon.classList.add('bi-heart');
            wishlistBtn.innerHTML = '<i class="bi bi-heart"></i> Yêu thích';
            wishlistBtn.title = 'Thêm vào yêu thích';
        }
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