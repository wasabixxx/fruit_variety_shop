@extends('layouts.app')

@section('title', 'Lịch sử Voucher - Fruit Variety Shop')

@section('content')
<div class="container py-4">
    <!-- Page Header -->
    <div class="mb-5">
        <div class="d-flex align-items-center justify-content-between">
            <div>
                <h1 class="h2 fw-bold mb-2">
                    <i class="bi bi-ticket-perforated text-primary me-2"></i>Lịch sử Voucher
                </h1>
                <p class="text-muted mb-0">Xem lại các voucher bạn đã sử dụng</p>
            </div>
            <a href="{{ route('vouchers.index') }}" class="btn btn-outline-primary">
                <i class="bi bi-collection me-2"></i>Xem voucher có sẵn
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row mb-4">
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                         style="width: 60px; height: 60px;">
                        <i class="bi bi-ticket-perforated fs-4 text-primary"></i>
                    </div>
                    <h4 class="fw-bold text-primary">{{ $stats['total_used'] }}</h4>
                    <p class="text-muted mb-0">Voucher đã dùng</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                         style="width: 60px; height: 60px;">
                        <i class="bi bi-currency-dollar fs-4 text-success"></i>
                    </div>
                    <h4 class="fw-bold text-success">{{ number_format($stats['total_saved'], 0, ',', '.') }}đ</h4>
                    <p class="text-muted mb-0">Tổng tiết kiệm</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                         style="width: 60px; height: 60px;">
                        <i class="bi bi-graph-up fs-4 text-info"></i>
                    </div>
                    <h4 class="fw-bold text-info">{{ number_format($stats['avg_discount'], 0, ',', '.') }}đ</h4>
                    <p class="text-muted mb-0">Tiết kiệm trung bình</p>
                </div>
            </div>
        </div>
        <div class="col-md-3 mb-3">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body text-center">
                    <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3"
                         style="width: 60px; height: 60px;">
                        <i class="bi bi-calendar-check fs-4 text-warning"></i>
                    </div>
                    <h4 class="fw-bold text-warning">{{ $stats['this_month'] }}</h4>
                    <p class="text-muted mb-0">Dùng trong tháng</p>
                </div>
            </div>
        </div>
    </div>

    @if($usedVouchers->count() > 0)
        <!-- Voucher History -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-bottom">
                <h5 class="fw-bold mb-0">
                    <i class="bi bi-clock-history me-2"></i>Lịch sử sử dụng
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Voucher</th>
                                <th>Đơn hàng</th>
                                <th>Giảm giá</th>
                                <th>Ngày sử dụng</th>
                                <th width="120">Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($usedVouchers as $usage)
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-3"
                                             style="width: 40px; height: 40px;">
                                            <i class="bi bi-ticket-perforated text-primary"></i>
                                        </div>
                                        <div>
                                            <div class="fw-bold">{{ $usage->voucher->name }}</div>
                                            <code class="bg-light px-2 py-1 rounded small">{{ $usage->voucher->code }}</code>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div>
                                        <span class="fw-semibold">#{{ $usage->order->id }}</span>
                                        <br>
                                        <small class="text-muted">{{ number_format($usage->order->total_amount, 0, ',', '.') }}đ</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-success-subtle text-success fs-6">
                                        -{{ number_format($usage->discount_amount, 0, ',', '.') }}đ
                                    </span>
                                </td>
                                <td>
                                    <div>
                                        <div class="fw-semibold">{{ $usage->used_at->format('d/m/Y') }}</div>
                                        <small class="text-muted">{{ $usage->used_at->format('H:i') }}</small>
                                    </div>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('orders.my-order-detail', $usage->order) }}" 
                                           class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        @if($usage->voucher->type === 'percentage')
                                            <button class="btn btn-sm btn-outline-info" 
                                                    title="Voucher giảm {{ $usage->voucher->amount }}%">
                                                <i class="bi bi-percent"></i>
                                            </button>
                                        @else
                                            <button class="btn btn-sm btn-outline-success" 
                                                    title="Voucher giảm {{ number_format($usage->voucher->amount, 0, ',', '.') }}đ">
                                                <i class="bi bi-currency-exchange"></i>
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            @if($usedVouchers->hasPages())
            <div class="card-footer bg-transparent">
                {{ $usedVouchers->links() }}
            </div>
            @endif
        </div>

        <!-- Available Vouchers Section -->
        <div class="mt-5">
            <div class="card border-0 shadow-sm bg-light">
                <div class="card-body text-center py-4">
                    <h5 class="fw-bold mb-3">
                        <i class="bi bi-gift me-2"></i>Tìm thêm voucher mới
                    </h5>
                    <p class="text-muted mb-3">Khám phá các voucher giảm giá hấp dẫn khác</p>
                    <a href="{{ route('vouchers.index') }}" class="btn btn-primary">
                        <i class="bi bi-collection me-2"></i>Xem tất cả voucher
                    </a>
                </div>
            </div>
        </div>
    @else
        <!-- Empty State -->
        <div class="text-center py-5">
            <div class="mb-4">
                <div class="bg-light rounded-circle d-inline-flex align-items-center justify-content-center"
                     style="width: 120px; height: 120px;">
                    <i class="bi bi-ticket-perforated display-4 text-muted"></i>
                </div>
            </div>
            <h3 class="fw-bold mb-3">Chưa có lịch sử sử dụng voucher</h3>
            <p class="text-muted mb-4 fs-5">
                Bạn chưa sử dụng voucher nào. Hãy khám phá các voucher giảm giá hấp dẫn!
            </p>
            <div class="d-flex justify-content-center gap-3">
                <a href="{{ route('vouchers.index') }}" class="btn btn-primary btn-lg">
                    <i class="bi bi-collection me-2"></i>Xem voucher
                </a>
                <a href="{{ route('products.index') }}" class="btn btn-outline-primary btn-lg">
                    <i class="bi bi-bag me-2"></i>Mua sắm ngay
                </a>
            </div>
        </div>
    @endif
</div>

<style>
.table th {
    border-top: none;
    font-weight: 600;
    color: #6c757d;
    font-size: 0.875rem;
}

.badge {
    font-size: 0.875rem;
}

code {
    font-size: 0.75rem;
    color: #495057;
}

.btn-group .btn {
    border-radius: 0.25rem !important;
}

.btn-group .btn:not(:last-child) {
    margin-right: 2px;
}

.card {
    transition: transform 0.2s ease, box-shadow 0.2s ease;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
}
</style>
@endsection