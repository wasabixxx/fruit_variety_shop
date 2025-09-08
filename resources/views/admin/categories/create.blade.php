@extends('admin.layout')

@section('title', 'Thêm danh mục mới - Admin Panel')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Quản lý Danh mục</a></li>
    <li class="breadcrumb-item active">Thêm mới</li>
@endsection

@section('content')
<!-- Page Title -->
<div class="page-title">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1>Thêm danh mục mới</h1>
            <p class="page-subtitle">Tạo danh mục sản phẩm mới</p>
        </div>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left me-2"></i>Quay lại danh sách
        </a>
    </div>
</div>

<div class="row justify-content-center">
    <div class="col-lg-8">
        <div class="admin-card card">
            <div class="admin-card-header">
                <h5 class="mb-0">
                    <i class="bi bi-plus-circle me-2"></i>
                    Thông tin danh mục
                </h5>
            </div>
            <div class="admin-card-body">
                <form action="{{ route('admin.categories.store') }}" method="POST">
                    @csrf
                    
                    <div class="row g-4">
                        <div class="col-12">
                            <label for="name" class="form-label required">Tên danh mục</label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
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
                                      placeholder="Nhập mô tả cho danh mục...">{{ old('description') }}</textarea>
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
                                   value="{{ old('color', '#6c757d') }}">
                            @error('color')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Màu hiển thị cho danh mục</small>
                        </div>

                        <div class="col-12">
                            <div class="d-flex gap-2">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-lg me-2"></i>Tạo danh mục
                                </button>
                                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-x-lg me-2"></i>Hủy
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
