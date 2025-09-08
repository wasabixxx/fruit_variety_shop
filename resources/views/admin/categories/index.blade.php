@extends('layouts.admin')

@section('title', 'Quản lý danh mục - Admin Panel')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Quản lý danh mục</li>
@endsection

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1">Quản lý danh mục</h1>
        <p class="text-muted mb-0">Quản lý tất cả danh mục sản phẩm</p>
    </div>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-admin-primary">
        <i class="bi bi-plus-circle me-2"></i>Thêm danh mục
    </a>
</div>

<!-- Categories Table -->
<div class="admin-card card">
    <div class="admin-card-header">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <div class="bg-primary bg-opacity-10 rounded p-2 me-3">
                    <i class="bi bi-tags text-primary"></i>
                </div>
                <h5 class="fw-bold mb-0">Danh sách danh mục</h5>
            </div>
        </div>
    </div>
    <div class="admin-card-body p-0">
        <div class="table-responsive">
            <table class="admin-table table mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên danh mục</th>
                        <th>Mô tả</th>
                        <th>Ngày tạo</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                    <tr>
                        <td><strong>#{{ $category->id }}</strong></td>
                        <td class="fw-semibold">{{ $category->name }}</td>
                        <td class="text-muted">{{ Str::limit($category->description, 50) }}</td>
                        <td class="text-muted">{{ $category->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('admin.categories.show', $category) }}" 
                                   class="btn btn-outline-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.categories.edit', $category) }}" 
                                   class="btn btn-outline-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.categories.destroy', $category) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" 
                                            onclick="return confirm('Bạn có chắc chắn muốn xóa danh mục này?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-5">
                            <i class="bi bi-tags fs-1 d-block mb-3 text-muted opacity-50"></i>
                            <p class="text-muted mb-3">Chưa có danh mục nào</p>
                            <a href="{{ route('admin.categories.create') }}" class="btn btn-admin-primary">
                                <i class="bi bi-plus-circle me-2"></i>Tạo danh mục đầu tiên
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
                                <i class="fas fa-plus"></i> Thêm danh mục đầu tiên
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        {{ $categories->links() }}
    </div>
</div>
@endsection
