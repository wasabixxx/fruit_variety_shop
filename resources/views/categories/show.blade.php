@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Breadcrumb -->
    <nav aria-label="breadcrumb" class="mb-4">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="/">Trang chủ</a></li>
            <li class="breadcrumb-item"><a href="{{ route('categories.index') }}">Danh mục</a></li>
            <li class="breadcrumb-item active">{{ $category->name }}</li>
        </ol>
    </nav>

    <!-- Category Header -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="bg-gradient text-white p-5 rounded-3" style="background: linear-gradient(135deg, #28a745, #20c997);">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h1 class="display-5 mb-3">
                            <i class="bi bi-flower2"></i> {{ $category->name }}
                        </h1>
                        @if($category->description)
                            <p class="lead">{{ $category->description }}</p>
                        @endif
                        <div class="d-flex align-items-center">
                            <span class="badge bg-light text-dark fs-6">
                                {{ $products->total() }} sản phẩm
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4 text-center">
                        <i class="bi bi-box-seam display-1 opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Products Grid -->
    @if($products->count() > 0)
        <div class="row">
            @foreach($products as $product)
            <div class="col-md-6 col-lg-4 mb-4">
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
            <p class="text-muted">Danh mục này hiện tại chưa có sản phẩm nào.</p>
            <div class="mt-4">
                <a href="{{ route('categories.index') }}" class="btn btn-outline-success me-2">
                    <i class="bi bi-arrow-left"></i> Xem danh mục khác
                </a>
                <a href="{{ route('products.index') }}" class="btn btn-success">
                    <i class="bi bi-box"></i> Xem tất cả sản phẩm
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