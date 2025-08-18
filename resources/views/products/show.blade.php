@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-6">
        @if($product->image)
            <img src="{{ $product->image }}" class="img-fluid rounded" alt="{{ $product->name }}">
        @else
            <img src="https://placehold.co/500x400?text={{ urlencode($product->name) }}" class="img-fluid rounded" alt="{{ $product->name }}">
        @endif
    </div>
    
    <div class="col-md-6">
        <h1>{{ $product->name }}</h1>
        
        <div class="mb-3">
            <span class="badge bg-primary">{{ $product->category->name ?? 'Chưa phân loại' }}</span>
        </div>
        
        <div class="mb-3">
            <h3 class="text-success">{{ number_format($product->price) }} VNĐ</h3>
        </div>
        
        <div class="mb-3">
            <strong>Tồn kho:</strong> 
            @if($product->stock > 0)
                <span class="text-success">{{ $product->stock }} sản phẩm</span>
            @else
                <span class="text-danger">Hết hàng</span>
            @endif
        </div>
        
        @if($product->description)
            <div class="mb-4">
                <h5>Mô tả sản phẩm:</h5>
                <p class="text-muted">{{ $product->description }}</p>
            </div>
        @endif
        
        <div class="d-flex gap-2">
            @if($product->stock > 0)
                <button class="btn btn-success btn-lg">
                    <i class="bi bi-cart-plus"></i> Thêm vào giỏ hàng
                </button>
            @else
                <button class="btn btn-secondary btn-lg" disabled>
                    <i class="bi bi-x-circle"></i> Hết hàng
                </button>
            @endif
            
            <a href="{{ route('products.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left"></i> Quay lại danh sách
            </a>
        </div>
    </div>
</div>

<div class="row mt-5">
    <div class="col-12">
        <h4>Sản phẩm cùng danh mục</h4>
        <div class="row">
            @php
                $relatedProducts = \App\Models\Product::where('category_id', $product->category_id)
                                                     ->where('id', '!=', $product->id)
                                                     ->take(4)
                                                     ->get();
            @endphp
            
            @forelse($relatedProducts as $related)
                <div class="col-md-3 mb-3">
                    <div class="card h-100">
                        @if($related->image)
                            <img src="{{ $related->image }}" class="card-img-top" alt="{{ $related->name }}" style="height: 150px; object-fit: cover;">
                        @else
                            <img src="https://placehold.co/300x150?text={{ urlencode($related->name) }}" class="card-img-top" alt="{{ $related->name }}" style="height: 150px; object-fit: cover;">
                        @endif
                        <div class="card-body">
                            <h6 class="card-title">{{ Str::limit($related->name, 30) }}</h6>
                            <p class="card-text text-success">{{ number_format($related->price) }} VNĐ</p>
                            <a href="{{ route('products.show', $related) }}" class="btn btn-sm btn-outline-primary">Xem chi tiết</a>
                        </div>
                    </div>
                </div>
            @empty
                <p class="text-muted">Không có sản phẩm cùng danh mục.</p>
            @endforelse
        </div>
    </div>
</div>
@endsection