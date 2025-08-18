@extends('layouts.app')

@section('content')
<h1>Chi tiết sản phẩm: {{ $product->name }}</h1>
<div class="card">
    <div class="card-body">
        <p><strong>ID:</strong> {{ $product->id }}</p>
        <p><strong>Tên:</strong> {{ $product->name }}</p>
        <p><strong>Danh mục:</strong> {{ $product->category->name }}</p>
        <p><strong>Giá:</strong> {{ number_format($product->price, 2) }} VNĐ</p>
        <p><strong>Mô tả:</strong> {{ $product->description ?? 'Không có mô tả' }}</p>
        <p><strong>Tồn kho:</strong> {{ $product->stock }}</p>
        @if($product->image)
            <p><strong>Hình ảnh:</strong><br>
            <img src="{{ asset($product->image) }}" alt="{{ $product->name }}" style="max-width: 200px;"></p>
        @endif
        <a href="{{ route('products.index') }}" class="btn btn-secondary">Quay lại</a>
        <a href="{{ route('products.edit', $product) }}" class="btn btn-warning">Sửa</a>
        <form action="{{ route('products.destroy', $product) }}" method="POST" style="display:inline;">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('Chắc chắn xóa?')">Xóa</button>
        </form>
    </div>
</div>
@endsection