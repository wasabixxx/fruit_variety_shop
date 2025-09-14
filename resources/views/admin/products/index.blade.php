@extends('admin.layout')

@section('title', 'Quản lý Sản phẩm - Admin Panel')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Quản lý Sản phẩm</li>
@endsection

@section('content')
<!-- Page Title -->
<div class="page-title">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1>Quản lý Sản phẩm</h1>
            <p class="page-subtitle">Quản lý toàn bộ sản phẩm trong hệ thống</p>
        </div>
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Thêm sản phẩm
        </a>
    </div>
</div>

<!-- Filter Section -->
<div class="admin-card card mb-4">
    <div class="admin-card-body">
        <form method="GET" action="{{ route('admin.products.index') }}" class="row g-3">
            <div class="col-md-4">
                <label class="form-label">Tìm kiếm</label>
                <div class="input-group">
                    <input type="text" name="search" class="form-control" placeholder="Tên sản phẩm..." 
                           value="{{ request('search') }}">
                    <button class="btn btn-outline-primary" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
            </div>
            <div class="col-md-3">
                <label class="form-label">Danh mục</label>
                <select name="category_id" class="form-select">
                    <option value="">Tất cả danh mục</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Trạng thái tồn kho</label>
                <select name="stock_status" class="form-select">
                    <option value="">Tất cả</option>
                    <option value="in_stock" {{ request('stock_status') == 'in_stock' ? 'selected' : '' }}>Còn hàng</option>
                    <option value="low_stock" {{ request('stock_status') == 'low_stock' ? 'selected' : '' }}>Sắp hết</option>
                    <option value="out_of_stock" {{ request('stock_status') == 'out_of_stock' ? 'selected' : '' }}>Hết hàng</option>
                </select>
            </div>
            <div class="col-md-2 d-flex align-items-end">
                <button type="submit" class="btn btn-primary w-100">
                    <i class="bi bi-funnel me-1"></i>Lọc
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Products Table -->
<div class="admin-card card">
    <div class="admin-card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-box me-2"></i>
                Danh sách Sản phẩm ({{ $products->total() }})
            </h5>
            <div class="d-flex gap-2">
                <span class="badge badge-success">{{ $products->where('stock', '>', 10)->count() }} còn hàng</span>
                <span class="badge badge-warning">{{ $products->whereBetween('stock', [1, 10])->count() }} sắp hết</span>
                <span class="badge badge-danger">{{ $products->where('stock', 0)->count() }} hết hàng</span>
            </div>
        </div>
    </div>
    <div class="admin-card-body p-0">
        <div class="table-responsive">
            <table class="table admin-table mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Hình ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Danh mục</th>
                        <th>Giá</th>
                        <th>Tồn kho</th>
                        <th>Trạng thái</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td>
                            <span class="fw-bold text-primary">#{{ $product->id }}</span>
                        </td>
                        <td>
                            @if($product->hasImage())
                                <img src="{{ $product->image_url }}" alt="{{ $product->name }}" 
                                     class="rounded" style="width: 50px; height: 50px; object-fit: cover;">
                            @else
                                <div class="bg-light rounded d-flex align-items-center justify-content-center" 
                                     style="width: 50px; height: 50px;">
                                    <i class="bi bi-image text-muted"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $product->name }}</div>
                            <small class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                        </td>
                        <td>
                            <span class="badge" style="background: {{ $product->category->color ?? '#6c757d' }}; color: white;">
                                {{ $product->category->name }}
                            </span>
                        </td>
                        <td>
                            <span class="fw-bold text-success">{{ number_format($product->price, 0, ',', '.') }}đ</span>
                        </td>
                        <td>
                            <span class="fw-semibold">{{ $product->stock }}</span>
                        </td>
                        <td>
                            @if($product->stock > 10)
                                <span class="badge badge-success">
                                    <i class="bi bi-check-circle me-1"></i>Còn hàng
                                </span>
                            @elseif($product->stock > 0)
                                <span class="badge badge-warning">
                                    <i class="bi bi-exclamation-triangle me-1"></i>Sắp hết
                                </span>
                            @else
                                <span class="badge badge-danger">
                                    <i class="bi bi-x-circle me-1"></i>Hết hàng
                                </span>
                            @endif
                        </td>
                        <td>
                            <span class="text-muted">{{ $product->created_at->format('d/m/Y') }}</span>
                            <br>
                            <small class="text-muted">{{ $product->created_at->diffForHumans() }}</small>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('admin.products.show', $product) }}" class="btn btn-outline-info btn-sm">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-outline-warning btn-sm">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.products.destroy', $product) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger btn-sm" 
                                            onclick="return confirmDelete('Bạn có chắc chắn muốn xóa sản phẩm này?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="9" class="text-center py-5">
                            <div class="empty-state">
                                <i class="bi bi-box"></i>
                                <h5>Chưa có sản phẩm nào</h5>
                                <p>Bắt đầu bằng cách thêm sản phẩm đầu tiên</p>
                                <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-2"></i>Thêm sản phẩm
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($products->hasPages())
    <div class="admin-card-body border-top">
        <div class="d-flex justify-content-center">
            {{ $products->appends(request()->query())->links() }}
        </div>
    </div>
    @endif
</div>

<!-- Product Stats -->
<div class="row g-4 mt-4">
    <div class="col-md-3">
        <div class="card stats-card primary">
            <div class="card-body text-center">
                <i class="bi bi-box fs-1 mb-2"></i>
                <h4>{{ $products->total() }}</h4>
                <p class="mb-0 opacity-75">Tổng sản phẩm</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card success">
            <div class="card-body text-center">
                <i class="bi bi-check-circle fs-1 mb-2"></i>
                <h4>{{ $products->where('stock', '>', 10)->count() }}</h4>
                <p class="mb-0 opacity-75">Còn hàng</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card warning">
            <div class="card-body text-center">
                <i class="bi bi-exclamation-triangle fs-1 mb-2"></i>
                <h4>{{ $products->whereBetween('stock', [1, 10])->count() }}</h4>
                <p class="mb-0 opacity-75">Sắp hết hàng</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card danger">
            <div class="card-body text-center">
                <i class="bi bi-x-circle fs-1 mb-2"></i>
                <h4>{{ $products->where('stock', 0)->count() }}</h4>
                <p class="mb-0 opacity-75">Hết hàng</p>
            </div>
        </div>
    </div>
</div>
@endsection
