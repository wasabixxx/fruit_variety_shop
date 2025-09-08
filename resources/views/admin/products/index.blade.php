@extends('layouts.admin')

@section('title', 'Quản lý sản phẩm - Admin Panel')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Quản lý sản phẩm</li>
@endsection

@section('content')
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 fw-bold text-dark mb-1">Quản lý sản phẩm</h1>
        <p class="text-muted mb-0">Quản lý tất cả sản phẩm trong cửa hàng</p>
    </div>
    <a href="{{ route('admin.products.create') }}" class="btn btn-admin-primary">
        <i class="bi bi-plus-circle me-2"></i>Thêm sản phẩm mới
    </a>
</div>

<!-- Products Table -->
<div class="admin-card card">
    <div class="admin-card-header">
        <div class="d-flex align-items-center justify-content-between">
            <div class="d-flex align-items-center">
                <div class="bg-primary bg-opacity-10 rounded p-2 me-3">
                    <i class="bi bi-box text-primary"></i>
                </div>
                <h5 class="fw-bold mb-0">Danh sách sản phẩm</h5>
            </div>
        </div>
    </div>
    <div class="admin-card-body p-0">
        <div class="table-responsive">
            <table class="admin-table table mb-0">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Hình ảnh</th>
                        <th>Tên sản phẩm</th>
                        <th>Danh mục</th>
                        <th>Giá</th>
                        <th>Tồn kho</th>
                        <th>Ngày tạo</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td><strong>#{{ $product->id }}</strong></td>
                        <td>
                            @if($product->image)
                                <img src="{{ $product->image }}" alt="{{ $product->name }}" 
                                     style="width: 50px; height: 50px; object-fit: cover;" class="rounded">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center rounded" 
                                     style="width: 50px; height: 50px;">
                                    <i class="bi bi-image text-muted"></i>
                                </div>
                            @endif
                        </td>
                        <td class="fw-semibold">{{ $product->name }}</td>
                        <td>
                            <span class="badge badge-admin-primary rounded-pill">
                                {{ $product->category->name ?? 'N/A' }}
                            </span>
                        </td>
                        <td class="fw-bold text-success">{{ number_format($product->price) }}đ</td>
                        <td>
                            @if($product->stock > 10)
                                <span class="badge badge-admin-success rounded-pill">{{ $product->stock }}</span>
                            @elseif($product->stock > 0)
                                <span class="badge badge-admin-warning rounded-pill">{{ $product->stock }}</span>
                            @else
                                <span class="badge badge-admin-danger rounded-pill">Hết hàng</span>
                            @endif
                        </td>
                        <td class="text-muted">{{ $product->created_at->format('d/m/Y') }}</td>
                        <td>
                            <div class="btn-group btn-group-sm" role="group">
                                <a href="{{ route('admin.products.show', $product) }}" 
                                   class="btn btn-outline-info">
                                    <i class="bi bi-eye"></i>
                                </a>
                                <a href="{{ route('admin.products.edit', $product) }}" 
                                   class="btn btn-outline-warning">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <form action="{{ route('admin.products.destroy', $product) }}" 
                                      method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-outline-danger" 
                                            onclick="return confirm('Bạn có chắc chắn muốn xóa sản phẩm này?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <i class="bi bi-box fs-1 d-block mb-3 text-muted opacity-50"></i>
                            <p class="text-muted mb-0">Chưa có sản phẩm nào</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-4">
                            <i class="fas fa-box fa-3x text-muted mb-3"></i>
                            <p class="text-muted">Chưa có sản phẩm nào</p>
                            <a href="{{ route('admin.products.create') }}" class="btn btn-success">
                                <i class="fas fa-plus"></i> Thêm sản phẩm đầu tiên
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        
        @if($products->hasPages())
            <div class="d-flex justify-content-center mt-4">
                {{ $products->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
