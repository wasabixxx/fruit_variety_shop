@extends('admin.layout')

@section('title', 'Quản lý Voucher - Admin Panel')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Quản lý Voucher</li>
@endsection

@section('content')
<!-- Page Title -->
<div class="page-title">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1>Quản lý Voucher</h1>
            <p class="page-subtitle">Quản lý mã giảm giá và khuyến mãi</p>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.vouchers.export') }}" class="btn btn-outline-success">
                <i class="bi bi-download me-2"></i>Xuất CSV
            </a>
            <a href="{{ route('admin.vouchers.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-2"></i>Tạo Voucher
            </a>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="admin-card card mb-4">
    <div class="admin-card-body">
        <h5 class="card-title mb-3">
            <i class="bi bi-funnel me-2"></i>Bộ lọc
        </h5>
        <form method="GET" action="{{ route('admin.vouchers.index') }}" class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Tìm kiếm</label>
                <input type="text" name="search" class="form-control" 
                       value="{{ request('search') }}" 
                       placeholder="Mã voucher, tên...">
            </div>
            <div class="col-md-2">
                <label class="form-label">Trạng thái</label>
                <select name="status" class="form-select">
                    <option value="">Tất cả</option>
                    <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Hoạt động</option>
                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Hết hạn</option>
                    <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Tạm dừng</option>
                    <option value="used_up" {{ request('status') === 'used_up' ? 'selected' : '' }}>Hết lượt</option>
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label">Loại</label>
                <select name="type" class="form-select">
                    <option value="">Tất cả</option>
                    <option value="percentage" {{ request('type') === 'percentage' ? 'selected' : '' }}>Phần trăm</option>
                    <option value="fixed" {{ request('type') === 'fixed' ? 'selected' : '' }}>Cố định</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">&nbsp;</label>
                <div class="d-flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search me-1"></i>Lọc
                    </button>
                    <a href="{{ route('admin.vouchers.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise me-1"></i>Reset
                    </a>
                </div>
            </div>
        </form>
    </div>
</div>

    <!-- Vouchers Table -->
    <div class="admin-card card">
        <div class="admin-card-body">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="card-title mb-0">
                    <i class="bi bi-ticket-perforated me-2"></i>Danh sách Voucher
                </h5>
                @if($vouchers->count() > 0)
                <div class="d-flex align-items-center gap-2">
                    <small class="text-muted">{{ $vouchers->total() }} voucher</small>
                    <div class="dropdown">
                        <button class="btn btn-outline-primary btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown">
                            <i class="bi bi-three-dots me-1"></i>Thao tác
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="#" onclick="selectAllVouchers()">
                                <i class="bi bi-check2-square me-2"></i>Chọn tất cả
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#" onclick="bulkAction('activate')">
                                <i class="bi bi-play-circle me-2"></i>Kích hoạt
                            </a></li>
                            <li><a class="dropdown-item" href="#" onclick="bulkAction('deactivate')">
                                <i class="bi bi-pause-circle me-2"></i>Tạm dừng
                            </a></li>
                            <li><a class="dropdown-item text-danger" href="#" onclick="bulkAction('delete')">
                                <i class="bi bi-trash me-2"></i>Xóa
                            </a></li>
                        </ul>
                    </div>
                </div>
                @endif
            </div>
            @if($vouchers->count() > 0)
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th width="40">
                                <input type="checkbox" class="form-check-input" id="selectAll">
                            </th>
                            <th>Mã Voucher</th>
                            <th>Tên</th>
                            <th>Loại</th>
                            <th>Giá trị</th>
                            <th>Sử dụng</th>
                            <th>Trạng thái</th>
                            <th>Hết hạn</th>
                            <th width="120">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($vouchers as $voucher)
                        <tr>
                            <td>
                                <input type="checkbox" class="form-check-input voucher-checkbox" value="{{ $voucher->id }}">
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <code class="bg-light px-2 py-1 rounded me-2">{{ $voucher->code }}</code>
                                    @if($voucher->is_public)
                                        <span class="badge bg-info-subtle text-info small">Công khai</span>
                                    @else
                                        <span class="badge bg-warning-subtle text-warning small">Riêng tư</span>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <div>
                                    <strong>{{ $voucher->name }}</strong>
                                    @if($voucher->description)
                                        <br><small class="text-muted">{{ Str::limit($voucher->description, 50) }}</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                @if($voucher->type === 'percentage')
                                    <span class="badge bg-success-subtle text-success">
                                        <i class="bi bi-percent me-1"></i>Phần trăm
                                    </span>
                                @else
                                    <span class="badge bg-primary-subtle text-primary">
                                        <i class="bi bi-currency-exchange me-1"></i>Cố định
                                    </span>
                                @endif
                            </td>
                            <td>
                                <strong class="text-primary">{{ $voucher->getDiscountText() }}</strong>
                                @if($voucher->minimum_order_amount)
                                    <br><small class="text-muted">Tối thiểu: {{ number_format($voucher->minimum_order_amount, 0, ',', '.') }}đ</small>
                                @endif
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <span class="badge bg-secondary me-2">{{ $voucher->used_count }}</span>
                                    @if($voucher->usage_limit)
                                        <small class="text-muted">/ {{ $voucher->usage_limit }}</small>
                                        <div class="progress ms-2" style="width: 60px; height: 6px;">
                                            <div class="progress-bar" style="width: {{ min(100, ($voucher->used_count / $voucher->usage_limit) * 100) }}%"></div>
                                        </div>
                                    @else
                                        <small class="text-muted">/ ∞</small>
                                    @endif
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-{{ $voucher->getStatusColor() }}">
                                    {{ $voucher->getStatusText() }}
                                </span>
                            </td>
                            <td>
                                @if($voucher->expires_at)
                                    <small class="text-muted">{{ $voucher->expires_at->format('d/m/Y H:i') }}</small>
                                @else
                                    <small class="text-muted">Không giới hạn</small>
                                @endif
                            </td>
                            <td>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('admin.vouchers.show', $voucher) }}" 
                                       class="btn btn-outline-primary btn-sm" title="Xem chi tiết">
                                        <i class="bi bi-eye"></i>
                                    </a>
                                    <a href="{{ route('admin.vouchers.edit', $voucher) }}" 
                                       class="btn btn-outline-secondary btn-sm" title="Chỉnh sửa">
                                        <i class="bi bi-pencil"></i>
                                    </a>
                                    <div class="dropdown">
                                        <button class="btn btn-outline-secondary btn-sm dropdown-toggle dropdown-toggle-split" 
                                                type="button" data-bs-toggle="dropdown"></button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <form action="{{ route('admin.vouchers.toggle-status', $voucher) }}" method="POST" class="d-inline">
                                                    @csrf
                                                    @method('PATCH')
                                                    <button type="submit" class="dropdown-item">
                                                        @if($voucher->is_active)
                                                            <i class="bi bi-pause-circle me-2"></i>Tạm dừng
                                                        @else
                                                            <i class="bi bi-play-circle me-2"></i>Kích hoạt
                                                        @endif
                                                    </button>
                                                </form>
                                            </li>
                                            @if($voucher->used_count === 0)
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <form action="{{ route('admin.vouchers.destroy', $voucher) }}" method="POST" 
                                                      onsubmit="return confirm('Bạn có chắc muốn xóa voucher này?')" class="d-inline">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="dropdown-item text-danger">
                                                        <i class="bi bi-trash me-2"></i>Xóa
                                                    </button>
                                                </form>
                                            </li>
                                            @endif
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            @if($vouchers->hasPages())
            <div class="card-footer">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted">
                        Hiển thị {{ $vouchers->firstItem() }} - {{ $vouchers->lastItem() }} 
                        trong tổng số {{ $vouchers->total() }} voucher
                    </div>
                    {{ $vouchers->appends(request()->query())->links() }}
                </div>
            </div>
            @endif
            @else
            <div class="text-center py-5">
                <i class="bi bi-ticket-perforated text-muted" style="font-size: 3rem;"></i>
                <h5 class="mt-3">Chưa có voucher nào</h5>
                <p class="text-muted mb-4">Tạo voucher đầu tiên để bắt đầu chương trình khuyến mãi</p>
                <a href="{{ route('admin.vouchers.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-lg me-2"></i>Tạo Voucher
                </a>
            </div>
            @endif
        </div>
    </div>

<!-- Bulk Actions Form -->
<form id="bulkActionForm" method="POST" action="{{ route('admin.vouchers.bulk-action') }}" style="display: none;">
    @csrf
    <input type="hidden" name="action" id="bulkAction">
    <input type="hidden" name="voucher_ids" id="voucherIds">
</form>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select all functionality
    const selectAllCheckbox = document.getElementById('selectAll');
    const voucherCheckboxes = document.querySelectorAll('.voucher-checkbox');
    
    selectAllCheckbox.addEventListener('change', function() {
        voucherCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
    
    voucherCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const checkedCount = document.querySelectorAll('.voucher-checkbox:checked').length;
            selectAllCheckbox.checked = checkedCount === voucherCheckboxes.length;
            selectAllCheckbox.indeterminate = checkedCount > 0 && checkedCount < voucherCheckboxes.length;
        });
    });
});

function selectAllVouchers() {
    document.getElementById('selectAll').click();
}

function bulkAction(action) {
    const checkedBoxes = document.querySelectorAll('.voucher-checkbox:checked');
    
    if (checkedBoxes.length === 0) {
        alert('Vui lòng chọn ít nhất một voucher');
        return;
    }
    
    const voucherIds = Array.from(checkedBoxes).map(cb => cb.value);
    
    let confirmMessage = '';
    switch(action) {
        case 'activate':
            confirmMessage = `Bạn có muốn kích hoạt ${checkedBoxes.length} voucher đã chọn?`;
            break;
        case 'deactivate':
            confirmMessage = `Bạn có muốn tạm dừng ${checkedBoxes.length} voucher đã chọn?`;
            break;
        case 'delete':
            confirmMessage = `Bạn có muốn xóa ${checkedBoxes.length} voucher đã chọn? Hành động này không thể hoàn tác.`;
            break;
    }
    
    if (confirm(confirmMessage)) {
        document.getElementById('bulkAction').value = action;
        document.getElementById('voucherIds').value = JSON.stringify(voucherIds);
        document.getElementById('bulkActionForm').submit();
    }
}
</script>
@endpush

@push('styles')
<style>
.table th {
    border-top: none;
    font-weight: 600;
    color: #6c757d;
    font-size: 0.875rem;
}

.badge {
    font-size: 0.75rem;
}

code {
    font-size: 0.875rem;
    color: #495057;
}

.progress {
    background-color: #e9ecef;
}

.btn-group .dropdown-toggle-split {
    border-left: none;
}
</style>
@endpush