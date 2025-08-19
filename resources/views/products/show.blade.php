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
                @if($product->image)
                    <img src="{{ $product->image }}" class="img-fluid rounded shadow" alt="{{ $product->name }}" style="width: 100%; max-height: 500px; object-fit: cover;">
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
                        <button class="btn btn-outline-success btn-lg">
                            <i class="bi bi-heart"></i> Yêu thích
                        </button>
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
                                @if($related->image)
                                    <img src="{{ $related->image }}" class="card-img-top" alt="{{ $related->name }}" style="height: 200px; object-fit: cover;">
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
</style>
@endsection