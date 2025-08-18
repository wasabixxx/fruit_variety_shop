@extends('admin.layouts.app')

@section('title', 'Chỉnh sửa sản phẩm')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Chỉnh sửa sản phẩm: {{ $product->name }}</h5>
    </div>
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('admin.products.update', $product) }}" method="POST">
            @csrf
            @method('PUT')
            
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="name" class="form-label">Tên sản phẩm</label>
                        <input type="text" class="form-control" id="name" name="name" 
                               value="{{ old('name', $product->name) }}" required>
                    </div>
                    
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Danh mục</label>
                        <select class="form-control" id="category_id" name="category_id" required>
                            <option value="">Chọn danh mục</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" 
                                        {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="price" class="form-label">Giá (VNĐ)</label>
                                <input type="number" class="form-control" id="price" name="price" 
                                       value="{{ old('price', $product->price) }}" min="0" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="stock" class="form-label">Tồn kho</label>
                                <input type="number" class="form-control" id="stock" name="stock" 
                                       value="{{ old('stock', $product->stock) }}" min="0" required>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="image" class="form-label">Hình ảnh</label>
                        @if($product->image)
                            <div class="mb-2">
                                <img src="{{ $product->image }}" alt="{{ $product->name }}" 
                                     style="width: 100px; height: 100px; object-fit: cover;" class="rounded">
                                <p class="small text-muted mt-1">Hình ảnh hiện tại</p>
                            </div>
                        @endif
                        <input type="url" class="form-control" id="image" name="image" 
                               value="{{ old('image', $product->image) }}" placeholder="https://example.com/image.jpg">
                        <small class="form-text text-muted">Nhập URL link ảnh từ internet (jpg, png, gif). Để trống nếu không muốn thay đổi.</small>
                    </div>
                    
                    <div class="mb-3">
                        <label for="description" class="form-label">Mô tả</label>
                        <textarea class="form-control" id="description" name="description" 
                                  rows="6">{{ old('description', $product->description) }}</textarea>
                    </div>
                </div>
            </div>
            
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-save"></i> Cập nhật sản phẩm
                </button>
                <a href="{{ route('admin.products.show', $product) }}" class="btn btn-info">
                    <i class="fas fa-eye"></i> Xem chi tiết
                </a>
                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Hủy
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
