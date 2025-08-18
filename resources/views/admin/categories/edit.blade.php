@extends('admin.layouts.app')

@section('title', 'Chỉnh sửa danh mục')

@section('content')
<div class="card">
    <div class="card-header">
        <h5>Chỉnh sửa danh mục: {{ $category->name }}</h5>
    </div>
    <div class="card-body">
        @if($errors->any())
            <div class="alert alert-danger">
                @foreach($errors->all() as $error)
                    <div>{{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form action="{{ route('admin.categories.update', $category) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label for="name" class="form-label">Tên danh mục</label>
                <input type="text" class="form-control" id="name" name="name" 
                       value="{{ old('name', $category->name) }}" required>
            </div>
            <div class="mb-3">
                <label for="description" class="form-label">Mô tả</label>
                <textarea class="form-control" id="description" name="description" 
                          rows="3">{{ old('description', $category->description) }}</textarea>
            </div>
            <div class="d-flex gap-2">
                <button type="submit" class="btn btn-warning">
                    <i class="fas fa-save"></i> Cập nhật
                </button>
                <a href="{{ route('admin.categories.show', $category) }}" class="btn btn-info">
                    <i class="fas fa-eye"></i> Xem chi tiết
                </a>
                <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
                    <i class="fas fa-times"></i> Hủy
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
