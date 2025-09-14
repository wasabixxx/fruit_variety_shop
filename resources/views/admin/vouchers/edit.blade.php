@extends('admin.layouts.app')

@section('title', 'Chỉnh sửa Voucher')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Chỉnh sửa Voucher</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.vouchers.index') }}">Quản lý Voucher</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.vouchers.show', $voucher) }}">{{ $voucher->code }}</a></li>
                    <li class="breadcrumb-item active">Chỉnh sửa</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.vouchers.show', $voucher) }}" class="btn btn-outline-info">
                <i class="bi bi-eye me-2"></i>Xem chi tiết
            </a>
            <a href="{{ route('admin.vouchers.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Quay lại
            </a>
        </div>
    </div>

    @if($voucher->used_count > 0)
    <div class="alert alert-warning mb-4">
        <i class="bi bi-exclamation-triangle me-2"></i>
        <strong>Lưu ý:</strong> Voucher này đã được sử dụng {{ $voucher->used_count }} lần. 
        Việc thay đổi một số thông tin có thể ảnh hưởng đến các đơn hàng đã áp dụng voucher.
    </div>
    @endif

    <form action="{{ route('admin.vouchers.update', $voucher) }}" method="POST" id="voucherForm">
        @csrf
        @method('PUT')
        <div class="row">
            <!-- Left Column -->
            <div class="col-lg-8">
                <!-- Basic Information -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-info-circle me-2"></i>Thông tin cơ bản
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="code" class="form-label">
                                    Mã Voucher <span class="text-danger">*</span>
                                    @if($voucher->used_count > 0)
                                        <span class="badge bg-warning-subtle text-warning ms-2">Không thể thay đổi</span>
                                    @endif
                                </label>
                                <div class="input-group">
                                    <input type="text" class="form-control @error('code') is-invalid @enderror" 
                                           id="code" name="code" value="{{ old('code', $voucher->code) }}" 
                                           placeholder="VD: SUMMER2024" required 
                                           {{ $voucher->used_count > 0 ? 'readonly' : '' }}>
                                    @if($voucher->used_count === 0)
                                    <button type="button" class="btn btn-outline-secondary" onclick="generateCode()">
                                        <i class="bi bi-arrow-clockwise"></i>
                                    </button>
                                    @endif
                                </div>
                                @error('code')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if($voucher->used_count > 0)
                                    <small class="form-text text-muted">Không thể thay đổi mã voucher đã được sử dụng</small>
                                @else
                                    <small class="form-text text-muted">Mã voucher phải duy nhất và không chứa ký tự đặc biệt</small>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label for="name" class="form-label">Tên voucher <span class="text-danger">*</span></label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" 
                                       id="name" name="name" value="{{ old('name', $voucher->name) }}" 
                                       placeholder="VD: Giảm giá mùa hè" required>
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-12">
                                <label for="description" class="form-label">Mô tả</label>
                                <textarea class="form-control @error('description') is-invalid @enderror" 
                                          id="description" name="description" rows="3" 
                                          placeholder="Mô tả chi tiết về voucher...">{{ old('description', $voucher->description) }}</textarea>
                                @error('description')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Discount Settings -->
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-percent me-2"></i>Cài đặt giảm giá
                        </h5>
                        @if($voucher->used_count > 0)
                            <span class="badge bg-warning-subtle text-warning">Hạn chế chỉnh sửa</span>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="type" class="form-label">
                                    Loại giảm giá <span class="text-danger">*</span>
                                    @if($voucher->used_count > 0)
                                        <span class="badge bg-warning-subtle text-warning ms-2">Không thể thay đổi</span>
                                    @endif
                                </label>
                                <select class="form-select @error('type') is-invalid @enderror" 
                                        id="type" name="type" required onchange="toggleDiscountFields()"
                                        {{ $voucher->used_count > 0 ? 'disabled' : '' }}>
                                    <option value="">Chọn loại giảm giá</option>
                                    <option value="percentage" {{ old('type', $voucher->type) === 'percentage' ? 'selected' : '' }}>Phần trăm (%)</option>
                                    <option value="fixed" {{ old('type', $voucher->type) === 'fixed' ? 'selected' : '' }}>Số tiền cố định (VNĐ)</option>
                                </select>
                                @if($voucher->used_count > 0)
                                    <input type="hidden" name="type" value="{{ $voucher->type }}">
                                @endif
                                @error('type')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6">
                                <label for="value" class="form-label">
                                    Giá trị <span class="text-danger">*</span>
                                    @if($voucher->used_count > 0)
                                        <span class="badge bg-warning-subtle text-warning ms-2">Chỉ có thể giảm</span>
                                    @endif
                                </label>
                                <div class="input-group">
                                    <input type="number" class="form-control @error('value') is-invalid @enderror" 
                                           id="value" name="value" value="{{ old('value', $voucher->value) }}" 
                                           min="0" step="0.01" required
                                           {{ $voucher->used_count > 0 ? 'max=' . $voucher->value : '' }}>
                                    <span class="input-group-text" id="valueUnit">
                                        {{ $voucher->type === 'percentage' ? '%' : 'VNĐ' }}
                                    </span>
                                </div>
                                @error('value')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if($voucher->used_count > 0)
                                    <small class="form-text text-muted">Chỉ có thể giảm giá trị, không thể tăng</small>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label for="minimum_order_amount" class="form-label">
                                    Đơn hàng tối thiểu
                                    @if($voucher->used_count > 0)
                                        <span class="badge bg-warning-subtle text-warning ms-2">Chỉ có thể giảm</span>
                                    @endif
                                </label>
                                <div class="input-group">
                                    <input type="number" class="form-control @error('minimum_order_amount') is-invalid @enderror" 
                                           id="minimum_order_amount" name="minimum_order_amount" 
                                           value="{{ old('minimum_order_amount', $voucher->minimum_order_amount) }}" 
                                           min="0"
                                           {{ $voucher->used_count > 0 && $voucher->minimum_order_amount ? 'max=' . $voucher->minimum_order_amount : '' }}>
                                    <span class="input-group-text">VNĐ</span>
                                </div>
                                @error('minimum_order_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if($voucher->used_count > 0)
                                    <small class="form-text text-muted">Chỉ có thể giảm yêu cầu tối thiểu</small>
                                @else
                                    <small class="form-text text-muted">Để trống nếu không có yêu cầu tối thiểu</small>
                                @endif
                            </div>
                            <div class="col-md-6" id="maxDiscountField" style="{{ $voucher->type === 'percentage' ? 'display: block' : 'display: none' }}">
                                <label for="maximum_discount_amount" class="form-label">Giảm tối đa</label>
                                <div class="input-group">
                                    <input type="number" class="form-control @error('maximum_discount_amount') is-invalid @enderror" 
                                           id="maximum_discount_amount" name="maximum_discount_amount" 
                                           value="{{ old('maximum_discount_amount', $voucher->maximum_discount_amount) }}" min="0">
                                    <span class="input-group-text">VNĐ</span>
                                </div>
                                @error('maximum_discount_amount')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Chỉ áp dụng cho voucher phần trăm</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Usage Limits -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-speedometer2 me-2"></i>Giới hạn sử dụng
                        </h5>
                    </div>
                    <div class="card-body">
                        @if($voucher->used_count > 0)
                        <div class="alert alert-info mb-3">
                            <i class="bi bi-info-circle me-2"></i>
                            Voucher đã được sử dụng <strong>{{ $voucher->used_count }}</strong> lần. 
                            Chỉ có thể tăng giới hạn sử dụng, không thể giảm.
                        </div>
                        @endif
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label for="usage_limit" class="form-label">Giới hạn tổng số lần sử dụng</label>
                                <input type="number" class="form-control @error('usage_limit') is-invalid @enderror" 
                                       id="usage_limit" name="usage_limit" 
                                       value="{{ old('usage_limit', $voucher->usage_limit) }}" 
                                       min="{{ $voucher->used_count > 0 ? $voucher->used_count : 1 }}">
                                @error('usage_limit')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                @if($voucher->used_count > 0)
                                    <small class="form-text text-muted">Tối thiểu {{ $voucher->used_count }} (đã sử dụng)</small>
                                @else
                                    <small class="form-text text-muted">Để trống nếu không giới hạn</small>
                                @endif
                            </div>
                            <div class="col-md-6">
                                <label for="usage_limit_per_user" class="form-label">Giới hạn mỗi người dùng</label>
                                <input type="number" class="form-control @error('usage_limit_per_user') is-invalid @enderror" 
                                       id="usage_limit_per_user" name="usage_limit_per_user" 
                                       value="{{ old('usage_limit_per_user', $voucher->usage_limit_per_user) }}" min="1">
                                @error('usage_limit_per_user')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                                <small class="form-text text-muted">Số lần tối đa một người có thể sử dụng</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Category Filter -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-tags me-2"></i>Áp dụng cho danh mục
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="category_filter" 
                                   id="all_categories" value="" 
                                   {{ $voucher->categories->isEmpty() ? 'checked' : '' }} 
                                   onchange="toggleCategorySelection()">
                            <label class="form-check-label" for="all_categories">
                                Áp dụng cho tất cả danh mục
                            </label>
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="radio" name="category_filter" 
                                   id="specific_categories" value="specific" 
                                   {{ $voucher->categories->isNotEmpty() ? 'checked' : '' }} 
                                   onchange="toggleCategorySelection()">
                            <label class="form-check-label" for="specific_categories">
                                Chỉ áp dụng cho các danh mục được chọn
                            </label>
                        </div>
                        <div id="categorySelection" style="{{ $voucher->categories->isNotEmpty() ? 'display: block' : 'display: none' }}">
                            <div class="row">
                                @php
                                    $selectedCategoryIds = $voucher->categories->pluck('id')->toArray();
                                @endphp
                                @forelse($categories as $category)
                                <div class="col-md-6 col-lg-4 mb-2">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" 
                                               name="category_ids[]" value="{{ $category->id }}" 
                                               id="category_{{ $category->id }}"
                                               {{ in_array($category->id, $selectedCategoryIds) ? 'checked' : '' }}>
                                        <label class="form-check-label" for="category_{{ $category->id }}">
                                            {{ $category->name }}
                                        </label>
                                    </div>
                                </div>
                                @empty
                                <div class="col-12">
                                    <p class="text-muted mb-0">Không có danh mục nào</p>
                                </div>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Column -->
            <div class="col-lg-4">
                <!-- Usage Statistics -->
                @if($voucher->used_count > 0)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-graph-up me-2"></i>Thống kê sử dụng
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row text-center">
                            <div class="col-6">
                                <div class="h4 mb-0 text-primary">{{ $voucher->used_count }}</div>
                                <small class="text-muted">Lần sử dụng</small>
                            </div>
                            <div class="col-6">
                                <div class="h4 mb-0 text-success">{{ number_format($voucher->voucherUsages->sum('discount_amount'), 0, ',', '.') }}đ</div>
                                <small class="text-muted">Tiết kiệm</small>
                            </div>
                        </div>
                        @if($voucher->usage_limit)
                        <div class="progress mt-3" style="height: 8px;">
                            <div class="progress-bar" style="width: {{ min(100, ($voucher->used_count / $voucher->usage_limit) * 100) }}%"></div>
                        </div>
                        <small class="text-muted">{{ $voucher->used_count }} / {{ $voucher->usage_limit }} lần</small>
                        @endif
                    </div>
                </div>
                @endif

                <!-- Status & Visibility -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-gear me-2"></i>Trạng thái & Hiển thị
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_active" 
                                       name="is_active" value="1" {{ old('is_active', $voucher->is_active) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">
                                    Kích hoạt voucher
                                </label>
                            </div>
                            <small class="form-text text-muted">Voucher chỉ có thể sử dụng khi được kích hoạt</small>
                        </div>
                        <div class="mb-3">
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" id="is_public" 
                                       name="is_public" value="1" {{ old('is_public', $voucher->is_public) ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_public">
                                    Voucher công khai
                                </label>
                            </div>
                            <small class="form-text text-muted">Voucher công khai sẽ hiển thị trong danh sách voucher khả dụng</small>
                        </div>
                    </div>
                </div>

                <!-- Validity Period -->
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-calendar-range me-2"></i>Thời gian hiệu lực
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="starts_at" class="form-label">Bắt đầu</label>
                            <input type="datetime-local" class="form-control @error('starts_at') is-invalid @enderror" 
                                   id="starts_at" name="starts_at" 
                                   value="{{ old('starts_at', $voucher->starts_at ? $voucher->starts_at->format('Y-m-d\TH:i') : '') }}">
                            @error('starts_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Để trống để áp dụng ngay lập tức</small>
                        </div>
                        <div class="mb-3">
                            <label for="expires_at" class="form-label">Kết thúc</label>
                            <input type="datetime-local" class="form-control @error('expires_at') is-invalid @enderror" 
                                   id="expires_at" name="expires_at" 
                                   value="{{ old('expires_at', $voucher->expires_at ? $voucher->expires_at->format('Y-m-d\TH:i') : '') }}">
                            @error('expires_at')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <small class="form-text text-muted">Để trống nếu voucher không hết hạn</small>
                        </div>
                    </div>
                </div>

                <!-- Preview -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-eye me-2"></i>Xem trước
                        </h5>
                    </div>
                    <div class="card-body">
                        <div id="voucherPreview" class="border rounded p-3 bg-light">
                            <!-- Preview will be updated by JavaScript -->
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Buttons -->
        <div class="row mt-4">
            <div class="col-12">
                <div class="d-flex justify-content-between">
                    <a href="{{ route('admin.vouchers.show', $voucher) }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left me-2"></i>Hủy
                    </a>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-lg me-2"></i>Cập nhật Voucher
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize form
    toggleDiscountFields();
    updatePreview();
    
    // Add event listeners for real-time preview
    const formFields = ['code', 'name', 'type', 'value', 'minimum_order_amount', 'maximum_discount_amount'];
    formFields.forEach(field => {
        const element = document.getElementById(field);
        if (element) {
            element.addEventListener('input', updatePreview);
            element.addEventListener('change', updatePreview);
        }
    });
});

function generateCode() {
    const characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
    let result = '';
    for (let i = 0; i < 8; i++) {
        result += characters.charAt(Math.floor(Math.random() * characters.length));
    }
    document.getElementById('code').value = result;
    updatePreview();
}

function toggleDiscountFields() {
    const type = document.getElementById('type').value;
    const valueUnit = document.getElementById('valueUnit');
    const maxDiscountField = document.getElementById('maxDiscountField');
    const valueInput = document.getElementById('value');
    
    if (type === 'percentage') {
        valueUnit.textContent = '%';
        maxDiscountField.style.display = 'block';
        if (!valueInput.disabled) {
            valueInput.max = '100';
            valueInput.placeholder = 'VD: 15';
        }
    } else if (type === 'fixed') {
        valueUnit.textContent = 'VNĐ';
        maxDiscountField.style.display = 'none';
        if (!valueInput.disabled) {
            valueInput.removeAttribute('max');
            valueInput.placeholder = 'VD: 50000';
        }
    } else {
        valueUnit.textContent = '-';
        maxDiscountField.style.display = 'none';
        if (!valueInput.disabled) {
            valueInput.removeAttribute('max');
            valueInput.placeholder = '';
        }
    }
    
    updatePreview();
}

function toggleCategorySelection() {
    const categorySelection = document.getElementById('categorySelection');
    const specificCategories = document.getElementById('specific_categories');
    
    if (specificCategories.checked) {
        categorySelection.style.display = 'block';
    } else {
        categorySelection.style.display = 'none';
        // Uncheck all category checkboxes
        document.querySelectorAll('input[name="category_ids[]"]').forEach(cb => {
            cb.checked = false;
        });
    }
}

function updatePreview() {
    const code = document.getElementById('code').value;
    const name = document.getElementById('name').value;
    const type = document.getElementById('type').value;
    const value = document.getElementById('value').value;
    const minOrder = document.getElementById('minimum_order_amount').value;
    const maxDiscount = document.getElementById('maximum_discount_amount').value;
    
    const preview = document.getElementById('voucherPreview');
    
    let discountText = '';
    if (type && value) {
        if (type === 'percentage') {
            discountText = `Giảm ${value}%`;
            if (maxDiscount) {
                discountText += ` (tối đa ${parseInt(maxDiscount).toLocaleString('vi-VN')}đ)`;
            }
        } else if (type === 'fixed') {
            discountText = `Giảm ${parseInt(value).toLocaleString('vi-VN')}đ`;
        }
    }
    
    let minOrderText = '';
    if (minOrder) {
        minOrderText = `<small class="text-muted d-block">Đơn hàng từ ${parseInt(minOrder).toLocaleString('vi-VN')}đ</small>`;
    }
    
    preview.innerHTML = `
        <div class="text-center">
            <div class="bg-primary text-white rounded p-2 mb-2 d-inline-block">
                <strong>${code || 'VOUCHER_CODE'}</strong>
            </div>
            <h6 class="mb-1">${name || 'Tên voucher'}</h6>
            <div class="text-primary fw-bold">${discountText || 'Giá trị giảm giá'}</div>
            ${minOrderText}
        </div>
    `;
}

// Form validation
document.getElementById('voucherForm').addEventListener('submit', function(e) {
    const type = document.getElementById('type').value;
    const value = parseFloat(document.getElementById('value').value);
    
    if (type === 'percentage' && value > 100) {
        e.preventDefault();
        alert('Giá trị phần trăm không được vượt quá 100%');
        return false;
    }
    
    const startsAt = document.getElementById('starts_at').value;
    const expiresAt = document.getElementById('expires_at').value;
    
    if (startsAt && expiresAt && new Date(startsAt) >= new Date(expiresAt)) {
        e.preventDefault();
        alert('Thời gian bắt đầu phải trước thời gian kết thúc');
        return false;
    }
});
</script>
@endpush

@push('styles')
<style>
.form-check-input:checked {
    background-color: #0d6efd;
    border-color: #0d6efd;
}

.card-header h5 {
    color: #495057;
}

#voucherPreview {
    min-height: 120px;
    display: flex;
    align-items: center;
}

.input-group-text {
    min-width: 50px;
    justify-content: center;
}

.badge {
    font-size: 0.75rem;
}
</style>
@endpush