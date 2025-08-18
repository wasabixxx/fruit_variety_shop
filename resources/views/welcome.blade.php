@extends('layouts.app')

@section('content')
<!-- Hero Section -->
<div class="hero-section mb-5">
    <div class="container">
        <div class="row align-items-center py-5">
            <div class="col-lg-6">
                <h1 class="display-3 fw-bold text-success mb-4">
                    <i class="bi bi-flower2"></i> Fruit Variety Shop
                </h1>
                <p class="lead text-muted mb-4">
                    Chuyên cung cấp hạt giống cây ăn quả chất lượng cao, 
                    giúp bạn tạo ra khu vườn xanh mát và thu hoạch những trái cây tươi ngon.
                </p>
                <div class="d-flex flex-wrap gap-3">
                    <a href="{{ route('products.index') }}" class="btn btn-success btn-lg">
                        <i class="bi bi-bag"></i> Xem sản phẩm
                    </a>
                    <a href="{{ route('categories.index') }}" class="btn btn-outline-success btn-lg">
                        <i class="bi bi-grid"></i> Danh mục
                    </a>
                </div>
            </div>
            <div class="col-lg-6 text-center">
                <img src="https://images.unsplash.com/photo-1560806887-1e4cd0b6cbd6?ixlib=rb-4.0.3&auto=format&fit=crop&w=500&q=80" 
                     alt="Fresh Fruits" class="img-fluid rounded shadow-lg" style="max-height: 400px;">
            </div>
        </div>
    </div>
</div>

<!-- Stats Section -->
<div class="container mb-5">
    <div class="row text-center">
        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm h-100 stats-card">
                <div class="card-body">
                    <i class="bi bi-box text-success display-4 mb-3"></i>
                    <h3 class="fw-bold">{{ $totalProducts }}+</h3>
                    <p class="text-muted">Sản phẩm</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm h-100 stats-card">
                <div class="card-body">
                    <i class="bi bi-grid text-success display-4 mb-3"></i>
                    <h3 class="fw-bold">{{ $totalCategories }}+</h3>
                    <p class="text-muted">Danh mục</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm h-100 stats-card">
                <div class="card-body">
                    <i class="bi bi-star text-success display-4 mb-3"></i>
                    <h3 class="fw-bold">100%</h3>
                    <p class="text-muted">Chất lượng</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-4">
            <div class="card border-0 shadow-sm h-100 stats-card">
                <div class="card-body">
                    <i class="bi bi-truck text-success display-4 mb-3"></i>
                    <h3 class="fw-bold">24/7</h3>
                    <p class="text-muted">Hỗ trợ</p>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Featured Products -->
@if($products->count() > 0)
<div class="container mb-5">
    <div class="row mb-4">
        <div class="col-12 text-center">
            <h2 class="display-5 fw-bold text-success mb-3">
                <i class="bi bi-star"></i> Sản phẩm nổi bật
            </h2>
            <p class="lead text-muted">Những hạt giống cây ăn quả được yêu thích nhất</p>
        </div>
    </div>
    
    <div class="row">
        @foreach($products as $product)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 shadow-sm product-card">
                @if($product->image)
                    <img src="{{ $product->image }}" class="card-img-top" alt="{{ $product->name }}" style="height: 250px; object-fit: cover;">
                @else
                    <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 250px;">
                        <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                    </div>
                @endif
                
                <div class="card-body d-flex flex-column">
                    <h5 class="card-title fw-bold">{{ $product->name }}</h5>
                    
                    <div class="mb-2">
                        <a href="{{ route('categories.show', $product->category) }}" class="text-decoration-none">
                            <small class="text-muted">
                                <i class="bi bi-tag"></i> {{ $product->category->name }}
                            </small>
                        </a>
                    </div>
                    
                    <div class="mb-3">
                        <span class="text-success fw-bold fs-4">
                            {{ number_format($product->price) }} VNĐ
                        </span>
                    </div>
                    
                    <div class="mb-3">
                        @if($product->stock > 10)
                            <span class="badge bg-success">Còn hàng ({{ $product->stock }})</span>
                        @elseif($product->stock > 0)
                            <span class="badge bg-warning">Sắp hết ({{ $product->stock }})</span>
                        @else
                            <span class="badge bg-danger">Hết hàng</span>
                        @endif
                    </div>
                    
                    @if($product->description)
                        <p class="card-text text-muted flex-grow-1">
                            {{ Str::limit($product->description, 100) }}
                        </p>
                    @endif
                    
                    <div class="mt-auto">
                        <a href="{{ route('products.show', $product) }}" class="btn btn-success w-100">
                            <i class="bi bi-eye"></i> Xem chi tiết
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    
    <div class="text-center mt-4">
        <a href="{{ route('products.index') }}" class="btn btn-outline-success btn-lg">
            <i class="bi bi-grid"></i> Xem tất cả sản phẩm
        </a>
    </div>
</div>
@else
<div class="container">
    <div class="text-center py-5">
        <i class="bi bi-inbox display-1 text-muted"></i>
        <h3 class="text-muted mt-3">Chưa có sản phẩm nào</h3>
        <p class="text-muted">Hệ thống đang được cập nhật, vui lòng quay lại sau.</p>
    </div>
</div>
@endif

<!-- Call to Action -->
<div class="bg-light py-5">
    <div class="container text-center">
        <h3 class="fw-bold text-success mb-3">Bắt đầu hành trình trồng trọt của bạn ngay hôm nay!</h3>
        <p class="lead text-muted mb-4">
            Hãy để chúng tôi giúp bạn tạo ra khu vườn trong mơ với những hạt giống chất lượng cao.
        </p>
        <a href="{{ route('products.index') }}" class="btn btn-success btn-lg me-3">
            <i class="bi bi-bag"></i> Mua ngay
        </a>
        <a href="#" class="btn btn-outline-success btn-lg">
            <i class="bi bi-telephone"></i> Liên hệ tư vấn
        </a>
    </div>
</div>

<style>
.hero-section {
    background: linear-gradient(135deg, rgba(40, 167, 69, 0.1), rgba(32, 201, 151, 0.1));
    border-radius: 15px;
}

.stats-card {
    transition: transform 0.3s ease;
}

.stats-card:hover {
    transform: translateY(-5px);
}

.product-card {
    transition: transform 0.3s ease;
    border: none;
}

.product-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}
</style>
@endsection