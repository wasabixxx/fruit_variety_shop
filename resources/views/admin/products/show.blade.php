@extends('admin.layouts.app')

@section('title', 'Chi tiết sản phẩm')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Chi tiết sản phẩm</h1>
    <div class="btn-group">
        <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning">
            <i class="fas fa-edit"></i> Chỉnh sửa
        </a>
        <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Hình ảnh sản phẩm</h5>
            </div>
            <div class="card-body text-center">
                @if($product->image)
                    <img src="{{ $product->image }}" alt="{{ $product->name }}" 
                         class="img-fluid rounded" style="max-height: 300px;">
                @else
                    <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                         style="height: 300px;">
                        <div class="text-center">
                            <i class="fas fa-image fa-3x text-muted mb-2"></i>
                            <p class="text-muted">Chưa có hình ảnh</p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5>Thông tin sản phẩm</h5>
            </div>
            <div class="card-body">
                <table class="table table-borderless">
                    <tr>
                        <th width="30%">ID:</th>
                        <td>{{ $product->id }}</td>
                    </tr>
                    <tr>
                        <th>Tên sản phẩm:</th>
                        <td><strong>{{ $product->name }}</strong></td>
                    </tr>
                    <tr>
                        <th>Danh mục:</th>
                        <td>
                            <span class="badge bg-primary">{{ $product->category->name ?? 'N/A' }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Giá:</th>
                        <td><strong class="text-success">{{ number_format($product->price) }} VNĐ</strong></td>
                    </tr>
                    <tr>
                        <th>Tồn kho:</th>
                        <td>
                            @if($product->stock > 10)
                                <span class="badge bg-success">{{ $product->stock }} sản phẩm</span>
                            @elseif($product->stock > 0)
                                <span class="badge bg-warning">{{ $product->stock }} sản phẩm</span>
                            @else
                                <span class="badge bg-danger">Hết hàng</span>
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Ngày tạo:</th>
                        <td>{{ $product->created_at->format('d/m/Y H:i') }}</td>
                    </tr>
                    <tr>
                        <th>Cập nhật:</th>
                        <td>{{ $product->updated_at->format('d/m/Y H:i') }}</td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
</div>

@if($product->description)
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5>Mô tả sản phẩm</h5>
            </div>
            <div class="card-body">
                <p>{{ $product->description }}</p>
            </div>
        </div>
    </div>
</div>
@endif
@endsection
