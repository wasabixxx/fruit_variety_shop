@extends('admin.layout')

@section('title', 'Chỉnh sửa sản phẩm - ' . $product->name . ' - Admin Panel')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Quản lý Sản phẩm</a></li>
    <li class="breadcrumb-item active">Chỉnh sửa {{ $product->name }}</li>
@endsection

@section('content')
<!-- Page Title -->
<div class="page-title">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1>Chỉnh sửa sản phẩm</h1>
            <p class="page-subtitle">Cập nhật thông tin sản phẩm "{{ $product->name }}"</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.products.show', $product) }}" class="btn btn-outline-info">
                <i class="bi bi-eye me-2"></i>Xem chi tiết
            </a>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left me-2"></i>Quay lại danh sách
            </a>
        </div>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="admin-card card">
            <div class="admin-card-header">
                <h5 class="mb-0">
                    <i class="bi bi-pencil me-2"></i>
                    Thông tin sản phẩm
                </h5>
            </div>
            <div class="admin-card-body">
                <form action="{{ route('admin.products.update', $product) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row g-4">
                        <div class="col-12">
                            <label for="name" class="form-label required">Tên sản phẩm</label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $product->name) }}" 
                                   placeholder="Nhập tên sản phẩm..."
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="category_id" class="form-label required">Danh mục</label>
                            <select class="form-select @error('category_id') is-invalid @enderror" 
                                    id="category_id" 
                                    name="category_id" 
                                    required>
                                <option value="">Chọn danh mục...</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" 
                                            {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="price" class="form-label required">Giá bán (VNĐ)</label>
                            <input type="number" 
                                   class="form-control @error('price') is-invalid @enderror" 
                                   id="price" 
                                   name="price" 
                                   value="{{ old('price', $product->price) }}" 
                                   placeholder="0"
                                   min="0"
                                   step="1000"
                                   required>
                            @error('price')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="stock" class="form-label required">Số lượng tồn kho</label>
                            <input type="number" 
                                   class="form-control @error('stock') is-invalid @enderror" 
                                   id="stock" 
                                   name="stock" 
                                   value="{{ old('stock', $product->stock) }}" 
                                   min="0"
                                   required>
                            @error('stock')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="image" class="form-label">URL Hình ảnh</label>
                            <input type="url" 
                                   class="form-control @error('image') is-invalid @enderror" 
                                   id="image" 
                                   name="image" 
                                   value="{{ old('image', $product->image) }}" 
                                   placeholder="https://example.com/image.jpg">
                            @error('image')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Để trống nếu không có hình ảnh</small>
                        </div>

                        <div class="col-12">
                            <label for="description" class="form-label">Mô tả sản phẩm</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="4" 
                                      placeholder="Nhập mô tả chi tiết cho sản phẩm...">{{ old('description', $product->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-warning">
                                    <i class="bi bi-check-lg me-2"></i>Cập nhật sản phẩm
                                </button>
                                <a href="{{ route('admin.products.show', $product) }}" class="btn btn-info">
                                    <i class="bi bi-eye me-2"></i>Xem chi tiết
                                </a>
                                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-lg me-2"></i>Hủy
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Danger Zone -->
        <div class="admin-card card mt-4 border-danger">
            <div class="admin-card-header bg-danger bg-opacity-10">
                <h5 class="mb-0 text-danger">
                    <i class="bi bi-exclamation-triangle me-2"></i>
                    Vùng nguy hiểm
                </h5>
            </div>
            <div class="admin-card-body">
                <p class="text-muted mb-3">
                    Xóa sản phẩm này sẽ xóa vĩnh viễn khỏi hệ thống. 
                    Hành động này không thể hoàn tác.
                </p>
                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger"
                            onclick="return confirmDelete('Bạn có chắc chắn muốn xóa sản phẩm này?')">
                        <i class="bi bi-trash me-2"></i>Xóa sản phẩm
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
