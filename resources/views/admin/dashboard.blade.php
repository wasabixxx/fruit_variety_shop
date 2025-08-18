@extends('admin.layouts.app')

@section('title', 'Dashboard')

@section('content')
<div class="row">
    <div class="col-md-3">
        <div class="card text-white bg-primary mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Tổng danh mục</span>
                <i class="fas fa-tags fa-2x"></i>
            </div>
            <div class="card-body">
                <h4 class="card-title">{{ $totalCategories }}</h4>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-white bg-success mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Tổng sản phẩm</span>
                <i class="fas fa-box fa-2x"></i>
            </div>
            <div class="card-body">
                <h4 class="card-title">{{ $totalProducts }}</h4>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-white bg-info mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Tổng người dùng</span>
                <i class="fas fa-users fa-2x"></i>
            </div>
            <div class="card-body">
                <h4 class="card-title">{{ $totalUsers }}</h4>
            </div>
        </div>
    </div>
    
    <div class="col-md-3">
        <div class="card text-white bg-warning mb-3">
            <div class="card-header d-flex justify-content-between align-items-center">
                <span>Quản trị viên</span>
                <i class="fas fa-user-shield fa-2x"></i>
            </div>
            <div class="card-body">
                <h4 class="card-title">1</h4>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title">Sản phẩm mới nhất</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Tên sản phẩm</th>
                                <th>Danh mục</th>
                                <th>Giá</th>
                                <th>Tồn kho</th>
                                <th>Ngày tạo</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($recentProducts as $product)
                            <tr>
                                <td>{{ $product->id }}</td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $product->category->name ?? 'N/A' }}</td>
                                <td>{{ number_format($product->price) }} VNĐ</td>
                                <td>{{ $product->stock }}</td>
                                <td>{{ $product->created_at->format('d/m/Y H:i') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
