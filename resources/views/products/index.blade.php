@extends('layouts.app')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1><i class="bi bi-bag"></i> Danh sách sản phẩm</h1>
    <a href="/" class="btn btn-outline-success">
        <i class="bi bi-house"></i> Về trang chủ
    </a>
</div>

<div class="row">
    @forelse($products as $product)
    <div class="col-md-4 col-lg-3 mb-4">
        <div class="card h-100">
            @if($product->image)
                <img src="{{ $product->image }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
            @else
                <img src="https://placehold.co/300x200?text={{ urlencode($product->name) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
            @endif
            <div class="card-body d-flex flex-column">
                <h5 class="card-title">{{ $product->name }}</h5>
                <p class="card-text">
                    <small class="text-muted">{{ $product->category->name ?? 'Chưa phân loại' }}</small>
                </p>
                <p class="card-text text-success fw-bold">{{ number_format($product->price) }} VNĐ</p>
                <p class="card-text">
                    <small class="text-muted">Tồn kho: {{ $product->stock }}</small>
                </p>
                @if($product->description)
                    <p class="card-text flex-grow-1">{{ Str::limit($product->description, 80) }}</p>
                @endif
                <div class="mt-auto">
                    <a href="{{ route('products.show', $product) }}" class="btn btn-primary w-100">
                        <i class="bi bi-eye"></i> Xem chi tiết
                    </a>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12">
        <div class="text-center py-5">
            <i class="bi bi-inbox display-1 text-muted"></i>
            <h3 class="text-muted mt-3">Chưa có sản phẩm nào</h3>
            <p class="text-muted">Hãy quay lại sau để xem các sản phẩm mới!</p>
            <a href="/" class="btn btn-success">Về trang chủ</a>
        </div>
    </div>
    @endforelse
</div>

@if($products->hasPages())
    <div class="d-flex justify-content-center mt-4">
        {{ $products->links() }}
    </div>
@endif
@endsection