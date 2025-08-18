@extends('layouts.app')

@section('content')
<h1 class="text-center mb-4">Fruit Variety Shop - Hạt giống cây ăn quả <i class="bi bi-flower2"></i></h1>
<div class="row">
    @forelse($products as $product)
    <div class="col-md-4 mb-4">
        <div class="card h-100">
            @if($product->image)
                <img src="{{ $product->image }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
            @else
                <img src="https://placehold.co/300x200?text={{ urlencode($product->name) }}" class="card-img-top" alt="{{ $product->name }}" style="height: 200px; object-fit: cover;">
            @endif
            <div class="card-body">
                <h5 class="card-title">{{ $product->name }}</h5>
                <p class="card-text">Danh mục: {{ $product->category->name }}</p>
                <p class="card-text text-success"><strong>Giá: {{ number_format($product->price, 0) }} VNĐ</strong></p>
                <p class="card-text">Tồn kho: {{ $product->stock }}</p>
                <a href="{{ route('products.show', $product) }}" class="btn btn-primary"><i class="bi bi-eye"></i> Xem chi tiết</a>
            </div>
        </div>
    </div>
    @empty
    <p class="text-center">Chưa có sản phẩm nào.</p>
    @endforelse
</div>
@endsection