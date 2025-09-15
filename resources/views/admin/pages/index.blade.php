@extends('admin.layout')

@section('title', 'Quản lý trang')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-2">Quản lý trang</h1>
        <p class="text-muted mb-0">Quản lý các trang tĩnh trên website</p>
    </div>
    <a href="{{ route('admin.pages.create') }}" class="btn btn-primary">
        <i class="bi bi-plus-lg me-1"></i>Tạo trang mới
    </a>
</div>

<!-- Statistics Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h6 class="card-title mb-0">Tổng trang</h6>
                        <h3 class="mb-0">{{ $stats['total'] }}</h3>
                    </div>
                    <div class="ms-3">
                        <i class="bi bi-file-text fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h6 class="card-title mb-0">Đã xuất bản</h6>
                        <h3 class="mb-0">{{ $stats['published'] }}</h3>
                    </div>
                    <div class="ms-3">
                        <i class="bi bi-check-circle fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h6 class="card-title mb-0">Bản nháp</h6>
                        <h3 class="mb-0">{{ $stats['draft'] }}</h3>
                    </div>
                    <div class="ms-3">
                        <i class="bi bi-file-earmark fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <div class="d-flex align-items-center">
                    <div class="flex-grow-1">
                        <h6 class="card-title mb-0">Trong menu</h6>
                        <h3 class="mb-0">{{ $stats['menu'] }}</h3>
                    </div>
                    <div class="ms-3">
                        <i class="bi bi-list fs-1"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.pages.index') }}">
            <div class="row g-3">
                <div class="col-md-4">
                    <label for="search" class="form-label">Tìm kiếm</label>
                    <input type="text" class="form-control" id="search" name="search" 
                           value="{{ request('search') }}" placeholder="Tìm theo tiêu đề, slug, nội dung...">
                </div>
                <div class="col-md-2">
                    <label for="status" class="form-label">Trạng thái</label>
                    <select class="form-select" id="status" name="status">
                        <option value="">Tất cả</option>
                        <option value="published" {{ request('status') == 'published' ? 'selected' : '' }}>Đã xuất bản</option>
                        <option value="draft" {{ request('status') == 'draft' ? 'selected' : '' }}>Bản nháp</option>
                        <option value="menu" {{ request('status') == 'menu' ? 'selected' : '' }}>Trong menu</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label for="template" class="form-label">Template</label>
                    <select class="form-select" id="template" name="template">
                        <option value="">Tất cả</option>
                        @foreach(App\Models\Page::getTemplates() as $key => $name)
                            <option value="{{ $key }}" {{ request('template') == $key ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-outline-primary me-2">
                        <i class="bi bi-search"></i> Tìm kiếm
                    </button>
                    <a href="{{ route('admin.pages.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise"></i> Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Bulk Actions -->
<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Danh sách trang ({{ $pages->total() }})</h5>
            <div class="d-flex gap-2">
                <button type="button" class="btn btn-outline-primary btn-sm" onclick="toggleSelectAll()">
                    <i class="bi bi-check-all"></i> Chọn tất cả
                </button>
                <div class="dropdown">
                    <button class="btn btn-outline-secondary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                        <i class="bi bi-gear"></i> Thao tác
                    </button>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="#" onclick="bulkAction('publish')">
                            <i class="bi bi-check-circle text-success"></i> Xuất bản
                        </a></li>
                        <li><a class="dropdown-item" href="#" onclick="bulkAction('unpublish')">
                            <i class="bi bi-pause-circle text-warning"></i> Hủy xuất bản
                        </a></li>
                        <li><a class="dropdown-item" href="#" onclick="bulkAction('add_to_menu')">
                            <i class="bi bi-list text-info"></i> Thêm vào menu
                        </a></li>
                        <li><a class="dropdown-item" href="#" onclick="bulkAction('remove_from_menu')">
                            <i class="bi bi-list text-muted"></i> Xóa khỏi menu
                        </a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="#" onclick="bulkAction('delete')">
                            <i class="bi bi-trash"></i> Xóa
                        </a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        @if($pages->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="40">
                                <input type="checkbox" id="selectAll" class="form-check-input">
                            </th>
                            <th>Tiêu đề</th>
                            <th width="120">Template</th>
                            <th width="100">Trạng thái</th>
                            <th width="80">Menu</th>
                            <th width="140">Ngày tạo</th>
                            <th width="140">Tác giả</th>
                            <th width="160">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pages as $page)
                        <tr>
                            <td>
                                <input type="checkbox" class="form-check-input page-checkbox" value="{{ $page->id }}">
                            </td>
                            <td>
                                <div>
                                    <h6 class="mb-1">
                                        <a href="{{ route('admin.pages.show', $page) }}" class="text-decoration-none">
                                            {{ $page->title }}
                                        </a>
                                    </h6>
                                    <small class="text-muted">
                                        <i class="bi bi-link-45deg"></i> {{ $page->slug }}
                                        @if($page->excerpt)
                                            <br>{{ Str::limit($page->excerpt, 80) }}
                                        @endif
                                    </small>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">{{ App\Models\Page::getTemplates()[$page->template] ?? $page->template }}</span>
                            </td>
                            <td>
                                <span class="badge {{ $page->getStatusBadgeClass() }}">
                                    {{ $page->getStatusText() }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($page->show_in_menu)
                                    <i class="bi bi-check-circle text-success" title="Hiển thị trong menu"></i>
                                @else
                                    <i class="bi bi-dash-circle text-muted" title="Không hiển thị trong menu"></i>
                                @endif
                            </td>
                            <td>
                                <small>
                                    {{ $page->created_at->format('d/m/Y') }}<br>
                                    <span class="text-muted">{{ $page->created_at->format('H:i') }}</span>
                                </small>
                            </td>
                            <td>
                                <small>
                                    {{ $page->creator->name ?? 'N/A' }}
                                </small>
                            </td>
                            <td>
                                <div class="btn-group btn-group-sm" role="group">
                                    <a href="{{ $page->getPublicUrl() }}" class="btn btn-outline-info" target="_blank" title="Xem trang">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-outline-primary" title="Chỉnh sửa">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <form method="POST" action="{{ route('admin.pages.toggle-status', $page) }}" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-outline-{{ $page->is_published ? 'warning' : 'success' }}" 
                                                onclick="return confirm('Bạn có chắc chắn muốn thay đổi trạng thái trang này?')"
                                                title="{{ $page->is_published ? 'Hủy xuất bản' : 'Xuất bản' }}">
                                            <i class="bi bi-{{ $page->is_published ? 'pause' : 'play' }}-circle"></i>
                                        </button>
                                    </form>
                                    <div class="btn-group btn-group-sm">
                                        <button type="button" class="btn btn-outline-secondary dropdown-toggle dropdown-toggle-split" 
                                                data-bs-toggle="dropdown"></button>
                                        <ul class="dropdown-menu">
                                            <li><a class="dropdown-item" href="{{ route('admin.pages.preview', $page) }}" target="_blank">
                                                <i class="bi bi-eye"></i> Xem trước
                                            </a></li>
                                            <li>
                                                <form method="POST" action="{{ route('admin.pages.duplicate', $page) }}" class="d-inline">
                                                    @csrf
                                                    <button type="submit" class="dropdown-item" 
                                                            onclick="return confirm('Bạn có muốn sao chép trang này?')"
                                                            style="border: none; background: none; width: 100%; text-align: left;">
                                                        <i class="bi bi-files"></i> Sao chép
                                                    </button>
                                                </form>
                                            </li>
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form method="POST" action="{{ route('admin.pages.destroy', $page) }}" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger" 
                                                            onclick="return confirm('Bạn có chắc chắn muốn xóa trang này? Hành động này không thể hoàn tác!')"
                                                            style="border: none; background: none; width: 100%; text-align: left;">
                                                        <i class="bi bi-trash"></i> Xóa
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            @if($pages->hasPages())
            <div class="card-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted">
                        Hiển thị {{ $pages->firstItem() }} đến {{ $pages->lastItem() }} trong {{ $pages->total() }} kết quả
                    </div>
                    {{ $pages->links() }}
                </div>
            </div>
            @endif
        @else
            <div class="text-center py-5">
                <i class="bi bi-file-text display-1 text-muted"></i>
                <h5 class="mt-3">Chưa có trang nào</h5>
                <p class="text-muted">Bạn chưa tạo trang nào. Hãy tạo trang đầu tiên!</p>
                <a href="{{ route('admin.pages.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg"></i> Tạo trang đầu tiên
                </a>
            </div>
        @endif
    </div>
</div>

<!-- Hidden forms for bulk actions only -->
<form id="bulkForm" method="POST" action="{{ route('admin.pages.bulk-action') }}" style="display: none;">
    @csrf
    <input type="hidden" name="action" id="bulkAction">
    <input type="hidden" name="page_ids" id="bulkPageIds">
</form>
@endsection

@push('scripts')
<script>
function toggleSelectAll() {
    const selectAll = document.getElementById('selectAll');
    const checkboxes = document.querySelectorAll('.page-checkbox');
    
    selectAll.checked = !selectAll.checked;
    checkboxes.forEach(checkbox => {
        checkbox.checked = selectAll.checked;
    });
}

function getSelectedIds() {
    const checkboxes = document.querySelectorAll('.page-checkbox:checked');
    return Array.from(checkboxes).map(checkbox => checkbox.value);
}

function bulkAction(action) {
    const selectedIds = getSelectedIds();
    
    if (selectedIds.length === 0) {
        alert('Vui lòng chọn ít nhất một trang!');
        return;
    }
    
    let message = '';
    switch(action) {
        case 'publish':
            message = `Bạn có chắc chắn muốn xuất bản ${selectedIds.length} trang?`;
            break;
        case 'unpublish':
            message = `Bạn có chắc chắn muốn hủy xuất bản ${selectedIds.length} trang?`;
            break;
        case 'delete':
            message = `Bạn có chắc chắn muốn xóa ${selectedIds.length} trang? Hành động này không thể hoàn tác!`;
            break;
        case 'add_to_menu':
            message = `Thêm ${selectedIds.length} trang vào menu?`;
            break;
        case 'remove_from_menu':
            message = `Xóa ${selectedIds.length} trang khỏi menu?`;
            break;
    }
    
    if (confirm(message)) {
        document.getElementById('bulkAction').value = action;
        document.getElementById('bulkPageIds').value = JSON.stringify(selectedIds);
        document.getElementById('bulkForm').submit();
    }
}
</script>
@endpush