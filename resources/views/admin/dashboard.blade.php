@extends('admin.layout')

@section('title', 'Dashboard - Admin Panel')

@section('breadcrumb')
    <li class="breadcrumb-item active">Dashboard</li>
@endsection

@section('content')
<!-- Page Title -->
<div class="page-title">
    <h1>Dashboard</h1>
    <p class="page-subtitle">Tổng quan hệ thống quản lý</p>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-5">
    <div class="col-md-3">
        <div class="card stats-card primary">
            <div class="card-body text-center">
                <i class="bi bi-tags fs-1 mb-2"></i>
                <h3 class="mb-1">{{ $totalCategories ?? 0 }}</h3>
                <p class="text-muted mb-0">Danh mục</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card success">
            <div class="card-body text-center">
                <i class="bi bi-box-seam fs-1 mb-2"></i>
                <h3 class="mb-1">{{ $totalProducts ?? 0 }}</h3>
                <p class="text-muted mb-0">Sản phẩm</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card warning">
            <div class="card-body text-center">
                <i class="bi bi-currency-dollar fs-1 mb-2"></i>
                <h3 class="mb-1">{{ number_format($totalRevenue ?? 0, 0, ',', '.') }}đ</h3>
                <p class="text-muted mb-0">Doanh thu</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card info">
            <div class="card-body text-center">
                <i class="bi bi-graph-up fs-1 mb-2"></i>
                <h3 class="mb-1">{{ number_format($monthlyTotal ?? 0, 0, ',', '.') }}đ</h3>
                <p class="text-muted mb-0">Tháng này</p>
            </div>
        </div>
    </div>
</div>

<!-- Charts Section -->
<div class="row g-4 mb-5">
    <!-- Revenue Chart -->
    <div class="col-lg-8">
        <div class="card chart-card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <div class="chart-icon bg-primary">
                        <i class="bi bi-bar-chart"></i>
                    </div>
                    <div class="ms-3">
                        <h5 class="mb-0">Doanh thu 7 ngày gần nhất</h5>
                        <small class="text-muted">Theo dõi xu hướng doanh thu hàng ngày</small>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="revenueChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Order Status Chart -->
    <div class="col-lg-4">
        <div class="card chart-card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <div class="chart-icon bg-success">
                        <i class="bi bi-pie-chart"></i>
                    </div>
                    <div class="ms-3">
                        <h5 class="mb-0">Trạng thái đơn hàng</h5>
                        <small class="text-muted">Phân bố trạng thái đơn hàng</small>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="orderStatusChart" width="200" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Top Products and Monthly Revenue -->
<div class="row g-4">
    <!-- Top Products -->
    <div class="col-lg-6">
        <div class="card chart-card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <div class="chart-icon bg-warning">
                        <i class="bi bi-trophy"></i>
                    </div>
                    <div class="ms-3">
                        <h5 class="mb-0">Top 5 sản phẩm bán chạy</h5>
                        <small class="text-muted">Sản phẩm có lượng bán cao nhất</small>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="topProductsChart" width="300" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Monthly Revenue Trend -->
    <div class="col-lg-6">
        <div class="card chart-card">
            <div class="card-header">
                <div class="d-flex align-items-center">
                    <div class="chart-icon bg-info">
                        <i class="bi bi-graph-up"></i>
                    </div>
                    <div class="ms-3">
                        <h5 class="mb-0">Doanh thu theo tháng</h5>
                        <small class="text-muted">Xu hướng doanh thu 6 tháng gần nhất</small>
                    </div>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container">
                    <canvas id="monthlyRevenueChart" width="300" height="200"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('extra-js')
<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
// Revenue Chart
const revenueCtx = document.getElementById('revenueChart').getContext('2d');

fetch('/admin/api/charts/daily-revenue')
    .then(response => response.json())
    .then(data => {
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: data.data,
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('vi-VN').format(value) + 'đ';
                            }
                        }
                    }
                }
            }
        });
    })
    .catch(error => {
        console.error('Error loading revenue chart:', error);
        // Hiển thị dữ liệu mẫu nếu lỗi
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: ['7 ngày trước', '6 ngày trước', '5 ngày trước', '4 ngày trước', '3 ngày trước', '2 ngày trước', 'Hôm qua'],
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: [1500000, 2000000, 1800000, 2200000, 1900000, 2500000, 2100000],
                    borderColor: '#667eea',
                    backgroundColor: 'rgba(102, 126, 234, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('vi-VN').format(value) + 'đ';
                            }
                        }
                    }
                }
            }
        });
    });

// Order Status Chart  
const orderStatusCtx = document.getElementById('orderStatusChart').getContext('2d');

fetch('/admin/api/charts/orders-by-status')
    .then(response => response.json())
    .then(data => {
        new Chart(orderStatusCtx, {
            type: 'doughnut',
            data: {
                labels: data.labels,
                datasets: [{
                    data: data.data,
                    backgroundColor: ['#28a745', '#ffc107', '#dc3545', '#17a2b8']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    })
    .catch(error => {
        console.error('Error loading order status chart:', error);
        // Hiển thị dữ liệu mẫu nếu lỗi
        new Chart(orderStatusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Đã giao', 'Đang xử lý', 'Đã hủy', 'Chờ thanh toán'],
                datasets: [{
                    data: [45, 25, 5, 25],
                    backgroundColor: ['#28a745', '#ffc107', '#dc3545', '#17a2b8']
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false
            }
        });
    });

// Top Products Chart
const topProductsCtx = document.getElementById('topProductsChart').getContext('2d');

fetch('/admin/api/charts/top-products')
    .then(response => response.json())
    .then(data => {
        new Chart(topProductsCtx, {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Số lượng bán',
                    data: data.data,
                    backgroundColor: '#ffc107'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y'
            }
        });
    })
    .catch(error => {
        console.error('Error loading top products chart:', error);
        // Hiển thị dữ liệu mẫu nếu lỗi
        new Chart(topProductsCtx, {
            type: 'bar',
            data: {
                labels: ['Táo Fuji', 'Cam Sành', 'Chuối Tiêu', 'Nho Xanh', 'Xoài Cát'],
                datasets: [{
                    label: 'Số lượng bán',
                    data: [85, 72, 65, 58, 45],
                    backgroundColor: '#ffc107'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                indexAxis: 'y'
            }
        });
    });

// Monthly Revenue Chart
const monthlyRevenueCtx = document.getElementById('monthlyRevenueChart').getContext('2d');

fetch('/admin/api/charts/monthly-revenue')
    .then(response => response.json())
    .then(data => {
        new Chart(monthlyRevenueCtx, {
            type: 'bar',
            data: {
                labels: data.labels,
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: data.data,
                    backgroundColor: '#17a2b8'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('vi-VN').format(value) + 'đ';
                            }
                        }
                    }
                }
            }
        });
    })
    .catch(error => {
        console.error('Error loading monthly revenue chart:', error);
        // Hiển thị dữ liệu mẫu nếu lỗi
        new Chart(monthlyRevenueCtx, {
            type: 'bar',
            data: {
                labels: ['T4/2025', 'T5/2025', 'T6/2025', 'T7/2025', 'T8/2025', 'T9/2025'],
                datasets: [{
                    label: 'Doanh thu (VNĐ)',
                    data: [45000000, 52000000, 48000000, 65000000, 58000000, 42000000],
                    backgroundColor: '#17a2b8'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('vi-VN').format(value) + 'đ';
                            }
                        }
                    }
                }
            }
        });
    });
</script>
@endsection
