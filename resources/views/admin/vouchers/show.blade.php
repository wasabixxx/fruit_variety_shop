@extends('admin.layouts.app')

@section('title', 'Chi tiết Voucher')

@section('content')
<div class="container-fluid">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-1">Chi tiết Voucher</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb mb-0">
                    <li class="breadcrumb-item"><a href="{{ route('admin.vouchers.index') }}">Quản lý Voucher</a></li>
                    <li class="breadcrumb-item active">{{ $voucher->code }}</li>
                </ol>
            </nav>
        </div>
        <div class="d-flex gap-2">
            <form action="{{ route('admin.vouchers.toggle-status', $voucher) }}" method="POST" class="d-inline">
                @csrf
                @method('PATCH')
                <button type="submit" class="btn btn-outline-{{ $voucher->is_active ? 'warning' : 'success' }}">
                    @if($voucher->is_active)
                        <i class="bi bi-pause-circle me-2"></i>Tạm dừng
                    @else
                        <i class="bi bi-play-circle me-2"></i>Kích hoạt
                    @endif
                </button>
            </form>
            <a href="{{ route('admin.vouchers.edit', $voucher) }}" class="btn btn-outline-primary">
                <i class="bi bi-pencil me-2"></i>Chỉnh sửa
            </a>
            <a href="{{ route('admin.vouchers.index') }}" class="btn btn-outline-secondary">
                <i class="bi bi-arrow-left me-2"></i>Quay lại
            </a>
        </div>
    </div>

    <div class="row">
        <!-- Left Column -->
        <div class="col-lg-8">
            <!-- Voucher Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-ticket-perforated me-2"></i>Thông tin Voucher
                    </h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Mã Voucher</label>
                                <div class="d-flex align-items-center">
                                    <code class="bg-light px-3 py-2 rounded me-3 fs-5">{{ $voucher->code }}</code>
                                    <button class="btn btn-outline-secondary btn-sm" onclick="copyToClipboard('{{ $voucher->code }}')">
                                        <i class="bi bi-clipboard"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted">Tên Voucher</label>
                                <div class="fw-semibold">{{ $voucher->name }}</div>
                            </div>
                            @if($voucher->description)
                            <div class="mb-3">
                                <label class="form-label text-muted">Mô tả</label>
                                <div>{{ $voucher->description }}</div>
                            </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label class="form-label text-muted">Loại giảm giá</label>
                                <div>
                                    @if($voucher->type === 'percentage')
                                        <span class="badge bg-success-subtle text-success fs-6">
                                            <i class="bi bi-percent me-1"></i>Phần trăm
                                        </span>
                                    @else
                                        <span class="badge bg-primary-subtle text-primary fs-6">
                                            <i class="bi bi-currency-exchange me-1"></i>Cố định
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label text-muted">Giá trị giảm giá</label>
                                <div class="h5 text-primary mb-0">{{ $voucher->getDiscountText() }}</div>
                            </div>
                            @if($voucher->minimum_order_amount)
                            <div class="mb-3">
                                <label class="form-label text-muted">Đơn hàng tối thiểu</label>
                                <div class="fw-semibold">{{ number_format($voucher->minimum_order_amount, 0, ',', '.') }}đ</div>
                            </div>
                            @endif
                            @if($voucher->maximum_discount_amount && $voucher->type === 'percentage')
                            <div class="mb-3">
                                <label class="form-label text-muted">Giảm tối đa</label>
                                <div class="fw-semibold">{{ number_format($voucher->maximum_discount_amount, 0, ',', '.') }}đ</div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            <!-- Usage Statistics -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-graph-up me-2"></i>Thống kê sử dụng
                    </h5>
                    @if($voucher->used_count > 0)
                    <a href="{{ route('admin.vouchers.export', ['voucher_id' => $voucher->id]) }}" class="btn btn-outline-success btn-sm">
                        <i class="bi bi-download me-1"></i>Xuất báo cáo
                    </a>
                    @endif
                </div>
                <div class="card-body">
                    <div class="row text-center mb-4">
                        <div class="col-3">
                            <div class="h3 mb-1 text-primary">{{ $voucher->used_count }}</div>
                            <small class="text-muted">Lần sử dụng</small>
                        </div>
                        <div class="col-3">
                            <div class="h3 mb-1 text-success">{{ number_format($voucher->voucherUsages->sum('discount_amount'), 0, ',', '.') }}đ</div>
                            <small class="text-muted">Tổng tiết kiệm</small>
                        </div>
                        <div class="col-3">
                            <div class="h3 mb-1 text-info">{{ $voucher->voucherUsages->unique('user_id')->count() }}</div>
                            <small class="text-muted">Người dùng</small>
                        </div>
                        <div class="col-3">
                            @if($voucher->usage_limit)
                                <div class="h3 mb-1 text-warning">{{ $voucher->usage_limit - $voucher->used_count }}</div>
                                <small class="text-muted">Còn lại</small>
                            @else
                                <div class="h3 mb-1 text-secondary">∞</div>
                                <small class="text-muted">Không giới hạn</small>
                            @endif
                        </div>
                    </div>

                    @if($voucher->usage_limit)
                    <div class="mb-3">
                        <div class="d-flex justify-content-between mb-1">
                            <span class="text-muted small">Tiến độ sử dụng</span>
                            <span class="text-muted small">{{ $voucher->used_count }} / {{ $voucher->usage_limit }}</span>
                        </div>
                        <div class="progress" style="height: 8px;">
                            <div class="progress-bar" style="width: {{ min(100, ($voucher->used_count / $voucher->usage_limit) * 100) }}%"></div>
                        </div>
                    </div>
                    @endif

                    @if($voucher->used_count > 0)
                    <!-- Usage Chart -->
                    <div class="row">
                        <div class="col-12">
                            <canvas id="usageChart" height="100"></canvas>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-4">
                        <i class="bi bi-graph-up text-muted" style="font-size: 3rem;"></i>
                        <h6 class="mt-3">Chưa có dữ liệu sử dụng</h6>
                        <p class="text-muted mb-0">Voucher chưa được sử dụng lần nào</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Recent Usage History -->
            @if($voucher->used_count > 0)
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-clock-history me-2"></i>Lịch sử sử dụng gần đây
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Người dùng</th>
                                    <th>Đơn hàng</th>
                                    <th>Giá trị đơn</th>
                                    <th>Giảm giá</th>
                                    <th>Thời gian</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($voucher->voucherUsages()->with(['user', 'order'])->latest()->take(10)->get() as $usage)
                                <tr>
                                    <td>
                                        @if($usage->user)
                                            <div class="d-flex align-items-center">
                                                <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center me-2" style="width: 32px; height: 32px;">
                                                    {{ strtoupper(substr($usage->user->name, 0, 1)) }}
                                                </div>
                                                <div>
                                                    <div class="fw-semibold">{{ $usage->user->name }}</div>
                                                    <small class="text-muted">{{ $usage->user->email }}</small>
                                                </div>
                                            </div>
                                        @else
                                            <span class="text-muted">Khách vãng lai</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($usage->order)
                                            <a href="#" class="text-decoration-none">
                                                #{{ $usage->order->id }}
                                            </a>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        @if($usage->order)
                                            <span class="fw-semibold">{{ number_format($usage->order->total_amount, 0, ',', '.') }}đ</span>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-success fw-semibold">-{{ number_format($usage->discount_amount, 0, ',', '.') }}đ</span>
                                    </td>
                                    <td>
                                        <span class="text-muted">{{ $usage->created_at->format('d/m/Y H:i') }}</span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted py-4">Không có lịch sử sử dụng</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Right Column -->
        <div class="col-lg-4">
            <!-- Status & Settings -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-gear me-2"></i>Trạng thái & Cài đặt
                    </h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label text-muted">Trạng thái</label>
                        <div>
                            <span class="badge bg-{{ $voucher->getStatusColor() }} fs-6">
                                {{ $voucher->getStatusText() }}
                            </span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Hiển thị</label>
                        <div>
                            @if($voucher->is_public)
                                <span class="badge bg-info-subtle text-info fs-6">
                                    <i class="bi bi-globe me-1"></i>Công khai
                                </span>
                            @else
                                <span class="badge bg-warning-subtle text-warning fs-6">
                                    <i class="bi bi-lock me-1"></i>Riêng tư
                                </span>
                            @endif
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Giới hạn sử dụng</label>
                        <div>
                            @if($voucher->usage_limit)
                                <span class="fw-semibold">{{ $voucher->usage_limit }} lần</span>
                            @else
                                <span class="text-muted">Không giới hạn</span>
                            @endif
                        </div>
                    </div>
                    @if($voucher->usage_limit_per_user)
                    <div class="mb-3">
                        <label class="form-label text-muted">Giới hạn mỗi người</label>
                        <div class="fw-semibold">{{ $voucher->usage_limit_per_user }} lần</div>
                    </div>
                    @endif
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
                        <label class="form-label text-muted">Bắt đầu</label>
                        <div>
                            @if($voucher->starts_at)
                                <span class="fw-semibold">{{ $voucher->starts_at->format('d/m/Y H:i') }}</span>
                                @if($voucher->starts_at > now())
                                    <span class="badge bg-warning-subtle text-warning ms-2">Chưa bắt đầu</span>
                                @endif
                            @else
                                <span class="text-success">Ngay lập tức</span>
                            @endif
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label text-muted">Kết thúc</label>
                        <div>
                            @if($voucher->expires_at)
                                <span class="fw-semibold">{{ $voucher->expires_at->format('d/m/Y H:i') }}</span>
                                @if($voucher->expires_at < now())
                                    <span class="badge bg-danger-subtle text-danger ms-2">Đã hết hạn</span>
                                @elseif($voucher->expires_at < now()->addDays(7))
                                    <span class="badge bg-warning-subtle text-warning ms-2">Sắp hết hạn</span>
                                @endif
                            @else
                                <span class="text-success">Không giới hạn</span>
                            @endif
                        </div>
                    </div>
                    @if($voucher->expires_at && $voucher->expires_at > now())
                    <div>
                        <label class="form-label text-muted">Thời gian còn lại</label>
                        <div class="fw-semibold text-info">
                            {{ $voucher->expires_at->diffForHumans() }}
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Category Filter -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-tags me-2"></i>Danh mục áp dụng
                    </h5>
                </div>
                <div class="card-body">
                    @if($voucher->categories->isEmpty())
                        <div class="text-center py-2">
                            <span class="badge bg-success-subtle text-success fs-6">
                                <i class="bi bi-check-circle me-1"></i>Tất cả danh mục
                            </span>
                        </div>
                    @else
                        <div class="d-flex flex-wrap gap-2">
                            @foreach($voucher->categories as $category)
                                <span class="badge bg-primary-subtle text-primary">{{ $category->name }}</span>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">
                        <i class="bi bi-lightning me-2"></i>Thao tác nhanh
                    </h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button class="btn btn-outline-primary btn-sm" onclick="copyVoucherUrl()">
                            <i class="bi bi-link me-2"></i>Sao chép link voucher
                        </button>
                        <button class="btn btn-outline-info btn-sm" onclick="shareVoucher()">
                            <i class="bi bi-share me-2"></i>Chia sẻ voucher
                        </button>
                        @if($voucher->used_count === 0)
                        <button class="btn btn-outline-secondary btn-sm" onclick="duplicateVoucher()">
                            <i class="bi bi-files me-2"></i>Sao chép voucher
                        </button>
                        @endif
                        @if($voucher->used_count === 0)
                        <form action="{{ route('admin.vouchers.destroy', $voucher) }}" method="POST" 
                              onsubmit="return confirm('Bạn có chắc muốn xóa voucher này? Hành động này không thể hoàn tác.')" class="d-inline">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                <i class="bi bi-trash me-2"></i>Xóa voucher
                            </button>
                        </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    @if($voucher->used_count > 0)
    // Usage Chart
    const ctx = document.getElementById('usageChart').getContext('2d');
    const usageData = @json($voucher->voucherUsages()
        ->selectRaw('DATE(created_at) as date, COUNT(*) as count')
        ->whereRaw('created_at >= DATE_SUB(NOW(), INTERVAL 30 DAY)')
        ->groupBy('date')
        ->orderBy('date')
        ->get()
        ->keyBy('date')
        ->map(fn($item) => $item->count)
    );
    
    // Generate last 30 days
    const dates = [];
    const data = [];
    for (let i = 29; i >= 0; i--) {
        const date = new Date();
        date.setDate(date.getDate() - i);
        const dateStr = date.toISOString().split('T')[0];
        dates.push(date.toLocaleDateString('vi-VN', { day: '2-digit', month: '2-digit' }));
        data.push(usageData[dateStr] || 0);
    }
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: dates,
            datasets: [{
                label: 'Lượt sử dụng',
                data: data,
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                borderWidth: 2,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            },
            plugins: {
                legend: {
                    display: false
                }
            }
        }
    });
    @endif
});

function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show toast or alert
        alert('Đã sao chép mã voucher: ' + text);
    });
}

function copyVoucherUrl() {
    const url = '{{ route("vouchers.show", $voucher->code) }}';
    navigator.clipboard.writeText(url).then(function() {
        alert('Đã sao chép link voucher');
    });
}

function shareVoucher() {
    const text = `Voucher giảm giá: ${@json($voucher->code)} - {{ $voucher->getDiscountText() }}`;
    const url = '{{ route("vouchers.show", $voucher->code) }}';
    
    if (navigator.share) {
        navigator.share({
            title: 'Voucher giảm giá',
            text: text,
            url: url
        });
    } else {
        copyVoucherUrl();
    }
}

function duplicateVoucher() {
    const url = '{{ route("admin.vouchers.create") }}' + '?duplicate={{ $voucher->id }}';
    window.location.href = url;
}
</script>
@endpush

@push('styles')
<style>
.badge {
    font-size: 0.75rem;
}

code {
    font-size: 1rem;
    color: #495057;
    background-color: #f8f9fa;
    border: 1px solid #dee2e6;
}

.progress {
    background-color: #e9ecef;
}

.card-header h5 {
    color: #495057;
}
</style>
@endpush