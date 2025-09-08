@extends('admin.layout')

@section('title', 'Chỉnh sửa danh mục - ' . $category->name . ' - Admin Panel')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Quản lý Danh mục</a></li>
    <li class="breadcrumb-item active">Chỉnh sửa {{ $category->name }}</li>
@endsection

@section('content')
<!-- Page Title -->
<div class="page-title">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1>Chỉnh sửa danh mục</h1>
            <p class="page-subtitle">Cập nhật thông tin danh mục "{{ $category->name }}"</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.categories.show', $category) }}" class="btn btn-outline-info">
                <i class="bi bi-eye me-2"></i>Xem chi tiết
            </a>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-primary">
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
                    Thông tin danh mục
                </h5>
            </div>
            <div class="admin-card-body">
                <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                    @csrf
                    @method('PUT')
                    
                    <div class="row g-4">
                        <div class="col-12">
                            <label for="name" class="form-label required">Tên danh mục</label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name', $category->name) }}" 
                                   placeholder="Nhập tên danh mục..."
                                   required>
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-12">
                            <label for="description" class="form-label">Mô tả</label>
                            <textarea class="form-control @error('description') is-invalid @enderror" 
                                      id="description" 
                                      name="description" 
                                      rows="4" 
                                      placeholder="Nhập mô tả cho danh mục...">{{ old('description', $category->description) }}</textarea>
                            @error('description')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="col-md-6">
                            <label for="color" class="form-label">Màu sắc</label>
                            <input type="color" 
                                   class="form-control form-control-color @error('color') is-invalid @enderror" 
                                   id="color" 
                                   name="color" 
                                   value="{{ old('color', $category->color ?? '#6c757d') }}">
                            @error('color')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Màu hiển thị cho danh mục</small>
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">Số sản phẩm</label>
                            <div class="form-control-plaintext">
                                <span class="badge badge-primary">{{ $category->products->count() }} sản phẩm</span>
                            </div>
                        </div>

                        <div class="col-12">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-warning">
                                    <i class="bi bi-check-lg me-2"></i>Cập nhật danh mục
                                </button>
                                <a href="{{ route('admin.categories.show', $category) }}" class="btn btn-info">
                                    <i class="bi bi-eye me-2"></i>Xem chi tiết
                                </a>
                                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
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
                    Xóa danh mục này sẽ ảnh hưởng đến tất cả sản phẩm thuộc danh mục. 
                    Hành động này không thể hoàn tác.
                </p>
                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-outline-danger"
                            onclick="return confirmDelete('Bạn có chắc chắn muốn xóa danh mục này? Tất cả sản phẩm trong danh mục sẽ bị ảnh hưởng.')">
                        <i class="bi bi-trash me-2"></i>Xóa danh mục
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
