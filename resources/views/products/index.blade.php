@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Hero Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="bg-gradient text-white p-5 rounded-3" style="background: linear-gradient(135deg, #28a745, #20c997);">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="display-4 mb-3">
                            <i class="bi bi-bag"></i> Tất cả sản phẩm
                        </h1>
                        <p class="lead">Khám phá bộ sưu tập trái cây tươi ngon của chúng tôi</p>
                        <div class="d-flex align-items-center">
                            <span class="badge bg-light text-dark fs-6">
                                {{ $products->total() }} sản phẩm có sẵn
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <i class="bi bi-basket display-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Category Filter -->
    @if($categories->count() > 0)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card shadow-sm">
                <div class="card-body">
                    <h5 class="card-title mb-3">
                        <i class="bi bi-funnel"></i> Lọc theo danh mục
                    </h5>
                    <div class="d-flex flex-wrap gap-2">
                        <a href="{{ route('products.index') }}" class="btn btn-outline-success">
                            <i class="bi bi-grid"></i> Tất cả ({{ $products->total() }})
                        </a>
                        @foreach($categories as $category)
                            <a href="{{ route('categories.show', $category) }}" class="btn btn-outline-success">
                                {{ $category->name }} ({{ $category->products_count }})
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Products Grid -->
    @if($products->count() > 0)
        <div class="row">
            @foreach($products as $product)
            <div class="col-md-6 col-lg-4 col-xl-3 mb-4">
                <div class="card h-100 shadow-sm product-card">
                    @if($product->image)
                        <img src="{{ $product->image }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                            <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                        </div>
                    @endif
                    
                    <div class="card-body d-flex flex-column">
                        <h5 class="card-title">{{ $product->name }}</h5>
                        
                        <div class="mb-2">
                            <small class="text-muted">
                                <i class="bi bi-tag"></i> {{ $product->category->name ?? 'Chưa phân loại' }}
                            </small>
                        </div>
                        
                        <div class="mb-2">
                            <span class="text-success fw-bold fs-5">
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
                                {{ Str::limit($product->description, 80) }}
                            </p>
                        @endif
                        
                        <div class="mt-auto d-flex gap-2">
                            <form method="POST" action="{{ route('cart.add', $product) }}" class="flex-grow-1">
                                @csrf
                                <input type="hidden" name="quantity" value="1">
                                <button type="submit" class="btn btn-outline-success w-100" @if($product->stock < 1) disabled @endif>
                                    <i class="bi bi-cart-plus"></i> Thêm vào giỏ
                                </button>
                            </form>
                            <a href="{{ route('products.show', $product) }}" class="btn btn-success">
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
                {{ $products->links() }}
            </div>
        @endif
    @else
        <div class="text-center py-5">
            <i class="bi bi-inbox display-1 text-muted"></i>
            <h3 class="text-muted mt-3">Chưa có sản phẩm nào</h3>
            <p class="text-muted">Hiện tại chưa có sản phẩm nào. Hãy quay lại sau!</p>
            <div class="mt-4">
                <a href="/" class="btn btn-success">
                    <i class="bi bi-house"></i> Về trang chủ
                </a>
            </div>
        </div>
    @endif
</div>

<style>
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