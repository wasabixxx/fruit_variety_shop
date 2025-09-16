@extends('admin.layout')

@section('title', 'Newsletter Subscribers')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2">Newsletter Subscribers</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.email-marketing.index') }}">Email Marketing</a></li>
                    <li class="breadcrumb-item active">Subscribers</li>
                </ol>
            </nav>
        </div>
        <div>
            <a href="{{ route('admin.email-marketing.index') }}" class="btn btn-secondary">
                <i class="bi bi-arrow-left"></i> Quay lại
            </a>
            <a href="{{ route('admin.email-marketing.create-promotion') }}" class="btn btn-primary">
                <i class="bi bi-envelope-plus"></i> Gửi Email
            </a>
        </div>
    </div>

    <!-- Stats -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-success">{{ $subscribers->total() }}</h5>
                    <p class="card-text">Tổng Subscribers</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-primary">{{ $subscribers->where('is_active', true)->count() }}</h5>
                    <p class="card-text">Đang hoạt động</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-warning">{{ $subscribers->where('is_active', false)->count() }}</h5>
                    <p class="card-text">Đã hủy đăng ký</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title text-info">{{ $subscribers->where('created_at', '>=', now()->subDays(7))->count() }}</h5>
                    <p class="card-text">Mới (7 ngày)</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Subscribers List -->
    <div class="card shadow">
        <div class="card-header">
            <h6 class="m-0 font-weight-bold text-primary">Danh sách Newsletter Subscribers</h6>
        </div>
        <div class="card-body">
            @if($subscribers->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Email</th>
                                <th>Tên</th>
                                <th>Trạng thái</th>
                                <th>Ngày đăng ký</th>
                                <th>Ngày hủy</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subscribers as $subscriber)
                                <tr>
                                    <td>
                                        <strong>{{ $subscriber->email }}</strong>
                                    </td>
                                    <td>
                                        {{ $subscriber->name ?: '-' }}
                                    </td>
                                    <td>
                                        @if($subscriber->is_active)
                                            <span class="badge bg-success">Hoạt động</span>
                                        @else
                                            <span class="badge bg-secondary">Đã hủy</span>
                                        @endif
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $subscriber->subscribed_at->format('d/m/Y H:i') }}
                                        </small>
                                    </td>
                                    <td>
                                        <small class="text-muted">
                                            {{ $subscriber->unsubscribed_at ? $subscriber->unsubscribed_at->format('d/m/Y H:i') : '-' }}
                                        </small>
                                    </td>
                                    <td>
                                        @if($subscriber->is_active)
                                            <form action="{{ route('newsletter.unsubscribe', $subscriber->unsubscribe_token) }}" 
                                                  method="GET" class="d-inline">
                                                <button type="submit" class="btn btn-sm btn-outline-warning" 
                                                        onclick="return confirm('Bạn có chắc muốn hủy đăng ký cho subscriber này?')">
                                                    <i class="bi bi-person-x"></i> Hủy đăng ký
                                                </button>
                                            </form>
                                        @else
                                            <form action="{{ route('newsletter.resubscribe', $subscriber->unsubscribe_token) }}" 
                                                  method="GET" class="d-inline">
                                                <button type="submit" class="btn btn-sm btn-outline-success">
                                                    <i class="bi bi-person-check"></i> Kích hoạt lại
                                                </button>
                                            </form>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-4">
                    {{ $subscribers->links() }}
                </div>
            @else
                <div class="text-center py-5">
                    <i class="bi bi-envelope-slash display-1 text-muted"></i>
                    <h5 class="mt-3 text-muted">Chưa có subscribers nào</h5>
                    <p class="text-muted">Khuyến khích khách hàng đăng ký newsletter để nhận thông tin khuyến mãi.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection