@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-success text-white"><h4><i class="bi bi-eye"></i> Chi tiết danh mục: {{ $category->name }}</h4></div>
    <div class="card-body">
        <p><strong>ID:</strong> {{ $category->id }}</p>
        <p><strong>Tên:</strong> {{ $category->name }}</p>
        <p><strong>Mô tả:</strong> {{ $category->description ?? 'Không có' }}</p>
        <a href="{{ route('categories.index') }}" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
        <a href="{{ route('categories.edit', $category) }}" class="btn btn-warning"><i class="bi bi-pencil"></i> Sửa</a>
        <form action="{{ route('categories.destroy', $category) }}" method="POST" style="display:inline;">
            @csrf @method('DELETE')
            <button type="submit" class="btn btn-danger" onclick="return confirm('Chắc chắn xóa?')"><i class="bi bi-trash"></i> Xóa</button>
        </form>
    </div>
</div>
@endsection