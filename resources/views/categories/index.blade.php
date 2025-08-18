@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header bg-success text-white"><h4><i class="bi bi-list-ul"></i> Danh sách danh mục</h4></div>
    <div class="card-body">
        <a href="{{ route('categories.create') }}" class="btn btn-primary mb-3"><i class="bi bi-plus-circle"></i> Thêm mới</a>
        <div class="table-responsive">
            <table class="table table-striped table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Tên</th>
                        <th>Mô tả</th>
                        <th>Hành động</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($categories as $category)
                    <tr>
                        <td>{{ $category->id }}</td>
                        <td>{{ $category->name }}</td>
                        <td>{{ $category->description ?? 'Không có' }}</td>
                        <td>
                            <a href="{{ route('categories.show', $category) }}" class="btn btn-sm btn-info"><i class="bi bi-eye"></i> Xem</a>
                            <a href="{{ route('categories.edit', $category) }}" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i> Sửa</a>
                            <form action="{{ route('categories.destroy', $category) }}" method="POST" style="display:inline;">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Chắc chắn xóa?')"><i class="bi bi-trash"></i> Xóa</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="4" class="text-center">Không có danh mục nào.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $categories->links() }} <!-- Pagination -->
    </div>
</div>
@endsection