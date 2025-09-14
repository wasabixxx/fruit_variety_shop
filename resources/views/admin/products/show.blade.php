@extends('admin.layout')

@section('title', 'Chi tiết sản phẩm - ' . $product->name . ' - Admin Panel')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item"><a href="{{ route('admin.products.index') }}">Quản lý Sản phẩm</a></li>
    <li class="breadcrumb-item active">{{ $product->name }}</li>
@endsection

@section('content')
<!-- Page Title -->
<div class="page-title">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1>Chi tiết sản phẩm</h1>
            <p class="page-subtitle">Xem thông tin chi tiết của sản phẩm</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-arrow-left me-2"></i>Quay lại danh sách
            </a>
            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning">
                <i class="bi bi-pencil me-2"></i>Chỉnh sửa
            </a>
            <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"
                        onclick="return confirmDelete('Bạn có chắc chắn muốn xóa sản phẩm này?')">
                    <i class="bi bi-trash me-2"></i>Xóa
                </button>
            </form>
        </div>
    </div>
</div>

<div class="row g-4">
    <!-- Product Information -->
    <div class="col-lg-8">
        <!-- Basic Info Card -->
        <div class="admin-card card mb-4">
            <div class="admin-card-header">
                <h5 class="mb-0">
                    <i class="bi bi-info-circle me-2"></i>
                    Thông tin cơ bản
                </h5>
            </div>
            <div class="admin-card-body">
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label text-muted">ID</label>
                        <div class="fw-bold text-primary fs-4">#{{ $product->id }}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Tên sản phẩm</label>
                        <div class="fw-bold fs-5">{{ $product->name }}</div>
                    </div>
                    <div class="col-12">
                        <label class="form-label text-muted">Mô tả</label>
                        <div class="fw-semibold">{{ $product->description ?? 'Chưa có mô tả' }}</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted">Danh mục</label>
                        <div>
                            <span class="badge" style="background: {{ $product->category->color ?? '#6c757d' }}; color: white; font-size: 0.9rem;">
                                {{ $product->category->name }}
                            </span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted">Giá bán</label>
                        <div class="fw-bold text-success fs-4">{{ number_format($product->price, 0, ',', '.') }}đ</div>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted">Tồn kho</label>
                        <div class="d-flex align-items-center">
                            <span class="fw-bold fs-4 me-2">{{ $product->stock }}</span>
                            @if($product->stock > 10)
                                <span class="badge badge-success">Còn hàng</span>
                            @elseif($product->stock > 0)
                                <span class="badge badge-warning">Sắp hết</span>
                            @else
                                <span class="badge badge-danger">Hết hàng</span>
                            @endif
                        </div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Ngày tạo</label>
                        <div class="fw-semibold">{{ $product->created_at->format('d/m/Y H:i') }}</div>
                        <small class="text-muted">{{ $product->created_at->diffForHumans() }}</small>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Cập nhật cuối</label>
                        <div class="fw-semibold">{{ $product->updated_at->format('d/m/Y H:i') }}</div>
                        <small class="text-muted">{{ $product->updated_at->diffForHumans() }}</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Product Image -->
        <div class="admin-card card">
            <div class="admin-card-header">
                <h5 class="mb-0">
                    <i class="bi bi-image me-2"></i>
                    Hình ảnh sản phẩm
                </h5>
            </div>
            <div class="admin-card-body text-center">
                @if($product->hasImage())
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                         class="img-fluid rounded shadow" style="max-height: 400px;">
                @else
                    <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                         style="height: 300px;">
                        <div class="text-center">
                            <i class="bi bi-image text-muted" style="font-size: 4rem;"></i>
                            <div class="mt-3 text-muted">Chưa có hình ảnh</div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Quick Actions -->
        <div class="admin-card card mb-4">
            <div class="admin-card-header">
                <h5 class="mb-0">
                    <i class="bi bi-gear me-2"></i>
                    Thao tác nhanh
                </h5>
            </div>
            <div class="admin-card-body d-flex flex-column gap-3">
                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-warning w-100">
                    <i class="bi bi-pencil me-2"></i>Chỉnh sửa sản phẩm
                </a>
                <a href="{{ route('products.show', $product) }}" target="_blank" class="btn btn-info w-100">
                    <i class="bi bi-eye me-2"></i>Xem trang công khai
                </a>
                <form action="{{ route('admin.products.destroy', $product) }}" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger w-100"
                            onclick="return confirmDelete('Bạn có chắc chắn muốn xóa sản phẩm này?')">
                        <i class="bi bi-trash me-2"></i>Xóa sản phẩm
                    </button>
                </form>
            </div>
        </div>

        <!-- Product Statistics -->
        <div class="admin-card card mb-4">
            <div class="admin-card-header">
                <h5 class="mb-0">
                    <i class="bi bi-bar-chart me-2"></i>
                    Thống kê
                </h5>
            </div>
            <div class="admin-card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Giá trị kho</span>
                    <span class="fw-bold text-success">{{ number_format($product->price * $product->stock, 0, ',', '.') }}đ</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span>Tình trạng</span>
                    @if($product->stock > 10)
                        <span class="badge badge-success">Còn hàng tốt</span>
                    @elseif($product->stock > 0)
                        <span class="badge badge-warning">Cần nhập thêm</span>
                    @else
                        <span class="badge badge-danger">Hết hàng</span>
                    @endif
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span>Danh mục</span>
                    <a href="{{ route('admin.categories.show', $product->category) }}" 
                       class="badge text-decoration-none" 
                       style="background: {{ $product->category->color ?? '#6c757d' }}; color: white;">
                        {{ $product->category->name }}
                    </a>
                </div>
            </div>
        </div>

        <!-- Quick Info -->
        <div class="admin-card card">
            <div class="admin-card-header">
                <h5 class="mb-0">
                    <i class="bi bi-info me-2"></i>
                    Thông tin nhanh
                </h5>
            </div>
            <div class="admin-card-body">
                <small class="text-muted">
                    <i class="bi bi-calendar3 me-1"></i>
                    Được tạo {{ $product->created_at->diffForHumans() }}
                </small>
                <br>
                <small class="text-muted">
                    <i class="bi bi-pencil me-1"></i>
                    Cập nhật {{ $product->updated_at->diffForHumans() }}
                </small>
                @if($product->stock <= 5 && $product->stock > 0)
                <div class="alert alert-warning mt-3 py-2">
                    <i class="bi bi-exclamation-triangle me-1"></i>
                    <small>Sản phẩm sắp hết hàng!</small>
                </div>
                @elseif($product->stock == 0)
                <div class="alert alert-danger mt-3 py-2">
                    <i class="bi bi-x-circle me-1"></i>
                    <small>Sản phẩm đã hết hàng!</small>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
