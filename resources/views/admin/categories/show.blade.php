@extends('admin.layout')

@section('title', 'Chi tiết danh mục - ' . $category->name . ' - Admin Panel')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.categories.index') }}">Quản lý Danh mục</a></li>
    <li class="breadcrumb-item active">{{ $category->name }}</li>
@endsection

@section('content')
<!-- Page Title -->
<div class="page-title">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1>Chi tiết danh mục</h1>
            <p class="page-subtitle">Xem thông tin chi tiết của danh mục</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.categories.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left me-2"></i>Quay lại danh sách
            </a>
            <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning">
                <i class="bi bi-pencil me-2"></i>Chỉnh sửa
            </a>
            <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"
                        onclick="return confirmDelete('Bạn có chắc chắn muốn xóa danh mục này?')">
                    <i class="bi bi-trash me-2"></i>Xóa
                </button>
            </form>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Category Information -->
    <div class="col-lg-8">
        <div class="admin-card card">
            <div class="admin-card-header">
                <h5 class="mb-0">
                    <i class="bi bi-info-circle me-2"></i>
                    Thông tin danh mục
                </h5>
            </div>
            <div class="admin-card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label text-muted">ID</label>
                        <div class="fw-bold text-primary fs-4">#{{ $category->id }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Tên danh mục</label>
                        <div class="fw-bold fs-5">{{ $category->name }}</div>
                    </div>
                    <div class="col-12">
                        <label class="form-label text-muted">Mô tả</label>
                        <div class="fw-semibold">{{ $category->description ?? 'Chưa có mô tả' }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Màu sắc</label>
                        <div class="d-flex align-items-center">
                            <div class="rounded-circle me-2" 
                                 style="width: 30px; height: 30px; background-color: {{ $category->color ?? '#6c757d' }};"></div>
                            <span class="fw-semibold">{{ $category->color ?? '#6c757d' }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Số sản phẩm</label>
                        <div class="fw-bold text-success fs-5">{{ $category->products->count() }} sản phẩm</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Ngày tạo</label>
                        <div class="fw-semibold">{{ $category->created_at->format('d/m/Y H:i') }}</div>
                        <small class="text-muted">{{ $category->created_at->diffForHumans() }}</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Cập nhật cuối</label>
                        <div class="fw-semibold">{{ $category->updated_at->format('d/m/Y H:i') }}</div>
                        <small class="text-muted">{{ $category->updated_at->diffForHumans() }}</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-lg-4">
        <div class="admin-card card">
            <div class="admin-card-header">
                <h5 class="mb-0">
                    <i class="bi bi-gear me-2"></i>
                    Thao tác nhanh
                </h5>
            </div>
            <div class="admin-card-body d-flex flex-column gap-3">
                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-warning w-100">
                    <i class="bi bi-pencil me-2"></i>Chỉnh sửa danh mục
                </a>
                <a href="{{ route('admin.products.create') }}" class="btn btn-success w-100">
                    <i class="bi bi-plus-circle me-2"></i>Thêm sản phẩm vào danh mục
                </a>
                <form action="{{ route('admin.categories.destroy', $category) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100"
                            onclick="return confirmDelete('Bạn có chắc chắn muốn xóa danh mục này? Tất cả sản phẩm trong danh mục cũng sẽ bị ảnh hưởng.')">
                        <i class="bi bi-trash me-2"></i>Xóa danh mục
                    </button>
                </form>
            </div>
        </div>

        <!-- Statistics Card -->
        <div class="admin-card card mt-4">
            <div class="admin-card-header">
                <h5 class="mb-0">
                    <i class="bi bi-bar-chart me-2"></i>
                    Thống kê
                </h5>
            </div>
            <div class="admin-card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Tổng sản phẩm</span>
                    <span class="badge badge-primary">{{ $category->products->count() }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Sản phẩm còn hàng</span>
                    <span class="badge badge-success">{{ $category->products->where('stock', '>', 0)->count() }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span>Sản phẩm hết hàng</span>
                    <span class="badge badge-danger">{{ $category->products->where('stock', 0)->count() }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Products in this Category -->
@if($category->products->count() > 0)
<div class="admin-card card mt-4">
    <div class="admin-card-header">
        <h5 class="mb-0">
            <i class="bi bi-box me-2"></i>
            Sản phẩm trong danh mục ({{ $category->products->count() }})
        </h5>
    </div>
    <div class="admin-card-body p-0">
        <div class="table-responsive">
            <table class="table admin-table mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Hình ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Giá</th>
                        <th>Tồn kho</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($category->products as $product)
                    <tr>
                        <td><span class="fw-bold text-primary">#{{ $product->id }}</span></td>
                        <td>
                            @if($product->image)
                                <img src="{{ $product->image }}" alt="{{ $product->name }}" 
                                     class="rounded" style="width: 40px; height: 40px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                     style="width: 40px; height: 40px;">
                                    <i class="bi bi-image text-muted"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $product->name }}</div>
                            <small class="text-muted">{{ Str::limit($product->description, 30) }}</small>
                        </td>
                        <td><span class="fw-bold text-success">{{ number_format($product->price, 0, ',', '.') }}đ</span></td>
                        <td><span class="fw-semibold">{{ $product->stock }}</span></td>
                        <td>
                            @if($product->stock > 10)
                                <span class="badge badge-success">Còn hàng</span>
                            @elseif($product->stock > 0)
                                <span class="badge badge-warning">Sắp hết</span>
                            @else
                                <span class="badge badge-danger">Hết hàng</span>
                            @endif
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('admin.products.show', $product) }}" class="btn btn-outline-info btn-sm">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-outline-warning btn-sm">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@else
<div class="admin-card card mt-4">
    <div class="admin-card-body text-center py-5">
        <div class="empty-state">
            <i class="bi bi-box"></i>
            <h5>Chưa có sản phẩm nào</h5>
            <p>Danh mục này chưa có sản phẩm nào</p>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Thêm sản phẩm đầu tiên
            </a>
        </div>
    </div>
</div>
@endif
@endsection
