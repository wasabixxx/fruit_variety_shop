@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-success text-white"><h4><i class="bi bi-bag"></i> Danh sách sản phẩm</h4></div>
    <div class="card-body">
        <a href="{{ route('products.create') }}" class="btn btn-primary mb-3"><i class="bi bi-plus-circle"></i> Thêm mới</a>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Tên</th>
                        <th>Danh mục</th>
                        <th>Giá (VNĐ)</th>
                        <th>Tồn kho</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($products as $product)
                    <tr>
                        <td>{{ $product->id }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->category->name }}</td>
                        <td>{{ number_format($product->price, 0) }}</td>
                        <td>{{ $product->stock }}</td>
                        <td>
                            <a href="{{ route('products.show', $product) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i> Xem</a>
                            <a href="{{ route('products.edit', $product) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Sửa</a>
                            <form action="{{ route('products.destroy', $product) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Chắc chắn xóa?')"><i class="bi bi-trash"></i> Xóa</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" class="text-center">Không có sản phẩm nào.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $products->links() }} <!-- Pagination -->
    </div>
</div>
@endsection