@extends('admin.layout')

@section('title', 'Email Marketing')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2">Email Marketing</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Email Marketing</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.email-marketing.create-promotion') }}" class="btn btn-primary">
            <i class="bi bi-envelope-plus"></i> Tạo email khuyến mãi
        </a>
    </div>

    <!-- Stats Cards -->
    <div class="row mb-4">
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Newsletter Subscribers
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($subscribersCount) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-envelope-check fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Registered Users
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($usersCount) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-people fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Recipients
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ number_format($totalSubscribers) }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-envelope-at fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Email Template
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">Ready</div>
                        </div>
                        <div class="col-auto">
                            <i class="bi bi-file-earmark-text fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Hành động nhanh</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('admin.email-marketing.create-promotion') }}" class="btn btn-outline-primary">
                            <i class="bi bi-megaphone"></i> Gửi email khuyến mãi
                        </a>
                        <a href="{{ route('admin.email-marketing.subscribers') }}" class="btn btn-outline-success">
                            <i class="bi bi-list-ul"></i> Quản lý subscribers
                        </a>
                        <a href="{{ route('admin.vouchers.create') }}" class="btn btn-outline-info">
                            <i class="bi bi-ticket-perforated"></i> Tạo voucher mới
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Subscribers gần đây</h6>
                </div>
                <div class="card-body">
                    @if($recentSubscribers->count() > 0)
                        <div class="list-group list-group-flush">
                            @foreach($recentSubscribers as $subscriber)
                                <div class="list-group-item d-flex justify-content-between align-items-center border-0 px-0">
                                    <div>
                                        <small class="text-muted">{{ $subscriber->email }}</small>
                                        @if($subscriber->name)
                                            <br><small>{{ $subscriber->name }}</small>
                                        @endif
                                    </div>
                                    <small class="text-muted">{{ $subscriber->created_at->diffForHumans() }}</small>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-muted mb-0">Chưa có subscribers nào.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection