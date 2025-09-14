@extends('layouts.app')

@section('title', 'Danh sách yêu thích')

@section('content')
<div class="container my-5">
    <!-- Header -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h2 class="text-primary mb-1">
                        <i class="bi bi-heart-fill text-danger"></i>
                        Danh sách yêu thích
                    </h2>
                    <p class="text-muted mb-0">Những sản phẩm bạn yêu thích</p>
                </div>
                <div class="text-end">
                    <span class="badge bg-primary fs-6">{{ $wishlists->total() }} sản phẩm</span>
                </div>
            </div>
        </div>
    </div>

    @if($wishlists->count() > 0)
        <!-- Wishlist Products -->
        <div class="row">
            @foreach($wishlists as $wishlist)
                <div class="col-lg-3 col-md-4 col-sm-6 mb-4">
                    <div class="card h-100 wishlist-card" data-product-id="{{ $wishlist->product->id }}">
                        <!-- Product Image -->
                        <div class="position-relative">
                            @if($wishlist->product->hasImage())
                                <img src="{{ $wishlist->product->image_url }}" 
                                     class="card-img-top" 
                                     alt="{{ $wishlist->product->name }}"
                                     style="height: 200px; object-fit: cover;">
                            @else
                                <div class="card-img-top bg-light d-flex align-items-center justify-content-center" 
                                     style="height: 200px;">
                                    <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                                </div>
                            @endif
                            
                            <!-- Remove from wishlist button -->
                            <button class="btn btn-danger btn-sm position-absolute top-0 end-0 m-2 remove-wishlist-btn"
                                    data-product-id="{{ $wishlist->product->id }}"
                                    title="Xóa khỏi yêu thích">
                                <i class="bi bi-heart-fill"></i>
                            </button>
                        </div>

                        <!-- Product Info -->
                        <div class="card-body d-flex flex-column">
                            <div class="mb-2">
                                <span class="badge bg-secondary">{{ $wishlist->product->category->name }}</span>
                            </div>
                            
                            <h6 class="card-title mb-2">
                                <a href="{{ route('products.show', $wishlist->product) }}" 
                                   class="text-decoration-none text-dark">
                                    {{ $wishlist->product->name }}
                                </a>
                            </h6>
                            
                            @if($wishlist->product->description)
                                <p class="card-text text-muted small mb-3" style="font-size: 0.85rem;">
                                    {{ Str::limit($wishlist->product->description, 80) }}
                                </p>
                            @endif
                            
                            <!-- Rating -->
                            @if($wishlist->product->total_reviews > 0)
                                <div class="mb-2">
                                    <div class="d-flex align-items-center">
                                        <div class="text-warning me-1">
                                            {{ $wishlist->product->rating_stars }}
                                        </div>
                                        <small class="text-muted">
                                            ({{ $wishlist->product->total_reviews }} đánh giá)
                                        </small>
                                    </div>
                                </div>
                            @endif

                            <!-- Price -->
                            <div class="mb-3">
                                <h5 class="text-primary mb-0">
                                    {{ number_format($wishlist->product->price, 0, ',', '.') }}đ
                                </h5>
                            </div>

                            <!-- Actions -->
                            <div class="mt-auto">
                                <div class="row g-2">
                                    <div class="col-8">
                                        <a href="{{ route('products.show', $wishlist->product) }}" 
                                           class="btn btn-outline-primary btn-sm w-100">
                                            <i class="bi bi-eye"></i> Xem chi tiết
                                        </a>
                                    </div>
                                    <div class="col-4">
                                        <form action="{{ route('cart.add', $wishlist->product) }}" method="POST" class="d-inline">
                                            @csrf
                                            <button type="submit" class="btn btn-primary btn-sm w-100" title="Thêm vào giỏ">
                                                <i class="bi bi-cart-plus"></i>
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <!-- Added date -->
                            <div class="mt-2">
                                <small class="text-muted">
                                    <i class="bi bi-clock"></i>
                                    Thêm ngày {{ $wishlist->created_at->format('d/m/Y') }}
                                </small>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        @if($wishlists->hasPages())
            <div class="row mt-4">
                <div class="col-12">
                    <div class="d-flex justify-content-center">
                        {{ $wishlists->links() }}
                    </div>
                </div>
            </div>
        @endif

    @else
        <!-- Empty Wishlist -->
        <div class="row">
            <div class="col-12">
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="bi bi-heart text-muted" style="font-size: 4rem;"></i>
                    </div>
                    <h4 class="text-muted mb-3">Danh sách yêu thích trống</h4>
                    <p class="text-muted mb-4">
                        Bạn chưa có sản phẩm nào trong danh sách yêu thích.<br>
                        Hãy khám phá và thêm những sản phẩm bạn yêu thích!
                    </p>
                    <a href="{{ route('products.index') }}" class="btn btn-primary">
                        <i class="bi bi-search"></i>
                        Khám phá sản phẩm
                    </a>
                </div>
            </div>
        </div>
    @endif
</div>

<!-- Include wishlist JavaScript -->
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle remove from wishlist
    document.querySelectorAll('.remove-wishlist-btn').forEach(button => {
        button.addEventListener('click', function() {
            const productId = this.dataset.productId;
            const card = this.closest('.wishlist-card');
            
            if (!confirm('Bạn có chắc muốn xóa sản phẩm này khỏi danh sách yêu thích?')) {
                return;
            }

            // Show loading state
            this.innerHTML = '<i class="bi bi-hourglass-split"></i>';
            this.disabled = true;

            // Send AJAX request
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
                    // Remove card with animation
                    card.style.transition = 'all 0.3s ease';
                    card.style.opacity = '0';
                    card.style.transform = 'scale(0.95)';
                    
                    setTimeout(() => {
                        card.remove();
                        
                        // Check if no more items
                        const remainingCards = document.querySelectorAll('.wishlist-card');
                        if (remainingCards.length === 0) {
                            location.reload(); // Reload to show empty state
                        }
                    }, 300);

                    // Show success message
                    showAlert('success', data.message);
                } else {
                    throw new Error(data.message || 'Có lỗi xảy ra');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showAlert('danger', 'Có lỗi xảy ra: ' + error.message);
                
                // Reset button
                this.innerHTML = '<i class="bi bi-heart-fill"></i>';
                this.disabled = false;
            });
        });
    });

    function showAlert(type, message) {
        // Remove existing alerts
        document.querySelectorAll('.alert-dismissible').forEach(alert => alert.remove());
        
        const alertHtml = `
            <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                ${message}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        `;
        
        // Insert alert at top of container
        const container = document.querySelector('.container');
        container.insertAdjacentHTML('afterbegin', alertHtml);
        
        // Auto dismiss after 3 seconds
        setTimeout(() => {
            const alert = container.querySelector('.alert-dismissible');
            if (alert) {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            }
        }, 3000);
    }
});
</script>
@endpush

@push('styles')
<style>
.wishlist-card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
    border: 1px solid rgba(0,0,0,0.08);
}

.wishlist-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
}

.remove-wishlist-btn {
    border-radius: 50%;
    width: 32px;
    height: 32px;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(220, 53, 69, 0.9);
    border: none;
    transition: all 0.2s ease;
}

.remove-wishlist-btn:hover {
    background: rgba(220, 53, 69, 1);
    transform: scale(1.1);
}

.badge {
    font-size: 0.75rem;
}
</style>
@endpush
@endsection