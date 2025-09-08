@extends('admin.layout')

@section('title', 'Quản lý Danh mục - Admin Panel')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Quản lý Danh mục</li>
@endsection

@section('content')
<!-- Page Title -->
<div class="page-title">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1>Quản lý Danh mục</h1>
            <p class="page-subtitle">Quản lý các danh mục sản phẩm</p>
        </div>
        <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle me-2"></i>Thêm danh mục
        </a>
    </div>
</div>

<!-- Categories Table -->
<div class="admin-card card">
    <div class="admin-card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">
                <i class="bi bi-tags me-2"></i>
                Danh sách Danh mục ({{ $categories->total() }})
            </h5>
            <div class="d-flex align-items-center">
                <input type="text" class="form-control search-filter me-3" placeholder="Tìm kiếm..." style="width: 250px;">
                <span class="badge badge-primary">{{ $categories->total() }} danh mục</span>
            </div>
        </div>
    </div>
    <div class="admin-card-body p-0">
        <div class="table-responsive">
            <table class="table admin-table mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên danh mục</th>
                        <th>Slug</th>
                        <th>Mô tả</th>
                        <th>Số sản phẩm</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                    <tr>
                        <td>
                            <span class="fw-bold text-primary">#{{ $category->id }}</span>
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $category->name }}</div>
                        </td>
                        <td>
                            <code class="bg-light px-2 py-1 rounded">{{ $category->slug }}</code>
                        </td>
                        <td>
                            <span class="text-muted">{{ Str::limit($category->description, 50) }}</span>
                        </td>
                        <td>
                            @if($category->products_count > 0)
                                <span class="badge badge-success">{{ $category->products_count }} sản phẩm</span>
                            @else
                                <span class="text-muted">Chưa có sản phẩm</span>
                            @endif
                        </td>
                        <td>
                            <span class="text-muted">{{ $category->created_at->format('d/m/Y') }}</span>
                            <br>
                            <small class="text-muted">{{ $category->created_at->diffForHumans() }}</small>
                        </td>
                        <td>
                            <div class="btn-group">
                                <a href="{{ route('admin.categories.show', $category) }}" class="btn btn-outline-info btn-sm">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-outline-warning btn-sm">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                @if($category->products_count == 0)
                                    <form action="{{ route('admin.categories.destroy', $category) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-outline-danger btn-sm" 
                                                onclick="return confirmDelete('Bạn có chắc chắn muốn xóa danh mục này?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                @else
                                    <button class="btn btn-outline-secondary btn-sm" disabled 
                                            title="Không thể xóa danh mục có sản phẩm">
                                        <i class="bi bi-lock"></i>
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5">
                            <div class="empty-state">
                                <i class="bi bi-tags"></i>
                                <h5>Chưa có danh mục nào</h5>
                                <p>Bắt đầu bằng cách tạo danh mục đầu tiên</p>
                                <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">
                                    <i class="bi bi-plus-circle me-2"></i>Thêm danh mục
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    
    @if($categories->hasPages())
    <div class="admin-card-body border-top">
        <div class="d-flex justify-content-center">
            {{ $categories->links() }}
        </div>
    </div>
    @endif
</div>

<!-- Category Stats -->
<div class="row g-4 mt-4">
    <div class="col-md-3">
        <div class="card stats-card primary">
            <div class="card-body text-center">
                <i class="bi bi-tags fs-1 mb-2"></i>
                <h4>{{ $categories->total() }}</h4>
                <p class="mb-0 opacity-75">Tổng danh mục</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card success">
            <div class="card-body text-center">
                <i class="bi bi-check-circle fs-1 mb-2"></i>
                <h4>{{ $categories->where('products_count', '>', 0)->count() }}</h4>
                <p class="mb-0 opacity-75">Có sản phẩm</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card warning">
            <div class="card-body text-center">
                <i class="bi bi-exclamation-circle fs-1 mb-2"></i>
                <h4>{{ $categories->where('products_count', 0)->count() }}</h4>
                <p class="mb-0 opacity-75">Chưa có sản phẩm</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card info">
            <div class="card-body text-center">
                <i class="bi bi-box fs-1 mb-2"></i>
                <h4>{{ $categories->sum('products_count') }}</h4>
                <p class="mb-0 opacity-75">Tổng sản phẩm</p>
            </div>
        </div>
    </div>
</div>
@endsection
