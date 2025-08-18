@extends('layouts.app')

@section('content')
<h1>Sửa danh mục: {{ $category->name }}</h1>
<form action="{{ route('categories.update', $category) }}" method="POST">
    @csrf @method('PUT')
    <div class="form-group">
        <label>Tên</label>
        <input type="text" name="name" class="form-control" value="{{ $category->name }}" required>
    </div>
    <div class="form-group">
        <label>Mô tả</label>
        <textarea name="description" class="form-control">{{ $category->description }}</textarea>
    </div>
    <button type="submit" class="btn btn-success">Cập nhật</button>
    <a href="{{ route('categories.index') }}" class="btn btn-secondary">Hủy</a>
</form>
@endsection