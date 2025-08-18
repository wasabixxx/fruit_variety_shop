@extends('admin.layouts.app')

@section('title', 'Chi tiết danh mục')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Chi tiết danh mục: {{ $category->name }}</h1>
    <div class="btn-group">
        <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Chỉnh sửa
        </a>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <table class="table table-borderless">
            <tr>
                <th width="20%">ID:</th>
                <td>{{ $category->id }}</td>
            </tr>
            <tr>
                <th>Tên danh mục:</th>
                <td><strong>{{ $category->name }}</strong></td>
            </tr>
            <tr>
                <th>Mô tả:</th>
                <td>{{ $category->description ?? 'Không có mô tả' }}</td>
            </tr>
            <tr>
                <th>Ngày tạo:</th>
                <td>{{ $category->created_at->format('d/m/Y H:i') }}</td>
            </tr>
            <tr>
                <th>Cập nhật lần cuối:</th>
                <td>{{ $category->updated_at->format('d/m/Y H:i') }}</td>
            </tr>
        </table>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header">
        <h5>Sản phẩm trong danh mục ({{ $category->products->count() }})</h5>
    </div>
    <div class="card-body">
        @if($category->products->count() > 0)
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Tên sản phẩm</th>
                            <th>Giá</th>
                            <th>Tồn kho</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($category->products as $product)
                        <tr>
                            <td>{{ $product->id }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ number_format($product->price) }} VNĐ</td>
                            <td>{{ $product->stock }}</td>
                            <td>
                                <a href="{{ route('admin.products.show', $product) }}" class="btn btn-sm btn-outline-info">
                                    <i class="fas fa-eye"></i>
                                </a>
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-warning">
                                    <i class="fas fa-edit"></i>
                                </a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="text-center py-4">
                <i class="fas fa-box fa-3x text-muted mb-3"></i>
                <p class="text-muted">Chưa có sản phẩm nào trong danh mục này</p>
                <a href="{{ route('admin.products.create') }}" class="btn btn-success">
                    <i class="fas fa-plus"></i> Thêm sản phẩm
                </a>
            </div>
        @endif
    </div>
</div>
@endsection
