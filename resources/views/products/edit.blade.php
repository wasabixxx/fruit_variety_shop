@extends('layouts.app')

@section('content')
<h1>Sửa sản phẩm: {{ $product->name }}</h1>
<form action="{{ route('products.update', $product) }}" method="POST">
    @csrf @method('PUT')
    <div class="form-group">
        <label>Tên</label>
        <input type="text" name="name" class="form-control" value="{{ $product->name }}" required>
    </div>
    <div class="form-group">
        <label>Danh mục</label>
        <select name="category_id" class="form-control" required>
            @foreach($categories as $category)
            <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                {{ $category->name }}
            </option>
            @endforeach
        </select>
    </div>
    <div class="form-group">
        <label>Giá</label>
        <input type="number" name="price" class="form-control" value="{{ $product->price }}" required>
    </div>
    <div class="form-group">
        <label>Mô tả</label>
        <textarea name="description" class="form-control">{{ $product->description }}</textarea>
    </div>
    <div class="form-group">
        <label>Hình ảnh (đường dẫn)</label>
        <input type="text" name="image" class="form-control" value="{{ $product->image }}">
    </div>
    <div class="form-group">
        <label>Tồn kho</label>
        <input type="number" name="stock" class="form-control" value="{{ $product->stock }}" required>
    </div>
    <button type="submit" class="btn btn-success">Cập nhật</button>
    <a href="{{ route('products.index') }}" class="btn btn-secondary">Hủy</a>
</form>
@endsection