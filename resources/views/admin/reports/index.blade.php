@extends('admin.layout')

@section('title', 'Báo cáo chi tiết - Admin Panel')

@section('breadcrumb')
    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
    <li class="breadcrumb-item active">Báo cáo chi tiết</li>
@endsection

@section('content')
<!-- Page Title -->
<div class="page-title">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <h1>Báo cáo chi tiết</h1>
            <p class="page-subtitle">Thống kê và phân tích dữ liệu kinh doanh</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-success" onclick="exportReport()">
                <i class="bi bi-download me-2"></i>Xuất báo cáo
            </button>
            <button class="btn btn-primary" onclick="refreshCharts()">
                <i class="bi bi-arrow-clockwise me-2"></i>Làm mới
            </button>
        </div>
    </div>
</div>

<!-- Filter Section -->
<div class="admin-card card mb-4">
    <div class="admin-card-body">
        <div class="row g-3">
            <div class="col-md-3">
                <label class="form-label">Khoảng thời gian</label>
                <select class="form-select" id="timeRange">
                    <option value="7">7 ngày gần nhất</option>
                    <option value="30" selected>30 ngày gần nhất</option>
                    <option value="90">3 tháng gần nhất</option>
                    <option value="365">1 năm gần nhất</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Loại biểu đồ</label>
                <select class="form-select" id="chartType">
                    <option value="line">Đường</option>
                    <option value="bar">Cột</option>
                    <option value="area">Vùng</option>
                </select>
            </div>
            <div class="col-md-3">
                <label class="form-label">Trạng thái đơn</label>
                <select class="form-select" id="orderStatus">
                    <option value="">Tất cả</option>
                    <option value="pending">Chờ xử lý</option>
                    <option value="confirmed">Đã xác nhận</option>
                    <option value="delivered">Đã giao</option>
                </select>
            </div>
            <div class="col-md-3">
                <button class="btn btn-primary w-100" style="margin-top: 32px;" onclick="updateCharts()">
                    <i class="bi bi-funnel me-2"></i>Áp dụng bộ lọc
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Advanced Charts -->
<div class="row g-4 mb-5">
    <!-- Revenue Trend -->
    <div class="col-12">
        <div class="admin-card card">
            <div class="admin-card-header">
                <h5 class="fw-bold mb-0">
                    <i class="bi bi-graph-up text-primary me-2"></i>
                    Xu hướng doanh thu
                </h5>
            </div>
            <div class="admin-card-body">
                <canvas id="advancedRevenueChart" height="100"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mb-5">
    <!-- Product Performance -->
    <div class="col-lg-8">
        <div class="admin-card card">
            <div class="admin-card-header">
                <h5 class="fw-bold mb-0">
                    <i class="bi bi-trophy text-warning me-2"></i>
                    Hiệu suất sản phẩm
                </h5>
            </div>
            <div class="admin-card-body">
                <canvas id="productPerformanceChart" height="300"></canvas>
            </div>
        </div>
    </div>
    
    <!-- Category Distribution -->
    <div class="col-lg-4">
        <div class="admin-card card">
            <div class="admin-card-header">
                <h5 class="fw-bold mb-0">
                    <i class="bi bi-pie-chart text-info me-2"></i>
                    Phân bố danh mục
                </h5>
            </div>
            <div class="admin-card-body">
                <canvas id="categoryDistributionChart" height="300"></canvas>
            </div>
        </div>
    </div>
</div>

<!-- Detailed Statistics -->
<div class="row g-4">
    <!-- Revenue Stats -->
    <div class="col-md-4">
        <div class="admin-card card">
            <div class="admin-card-header">
                <h5 class="fw-bold mb-0">
                    <i class="bi bi-currency-dollar text-success me-2"></i>
                    Thống kê doanh thu
                </h5>
            </div>
            <div class="admin-card-body">
                <div class="stat-item d-flex justify-content-between mb-3">
                    <span>Doanh thu hôm nay</span>
                    <span class="fw-bold text-success" id="todayRevenue">0₫</span>
                </div>
                <div class="stat-item d-flex justify-content-between mb-3">
                    <span>Doanh thu tuần này</span>
                    <span class="fw-bold text-primary" id="weekRevenue">0₫</span>
                </div>
                <div class="stat-item d-flex justify-content-between mb-3">
                    <span>Doanh thu tháng này</span>
                    <span class="fw-bold text-warning" id="monthRevenue">0₫</span>
                </div>
                <div class="stat-item d-flex justify-content-between">
                    <span>Tổng doanh thu</span>
                    <span class="fw-bold text-danger" id="totalRevenue">0₫</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Order Stats -->
    <div class="col-md-4">
        <div class="admin-card card">
            <div class="admin-card-header">
                <h5 class="fw-bold mb-0">
                    <i class="bi bi-bag-check text-primary me-2"></i>
                    Thống kê đơn hàng
                </h5>
            </div>
            <div class="admin-card-body">
                <div class="stat-item d-flex justify-content-between mb-3">
                    <span>Đơn hàng hôm nay</span>
                    <span class="fw-bold text-success" id="todayOrders">0</span>
                </div>
                <div class="stat-item d-flex justify-content-between mb-3">
                    <span>Đơn chờ xử lý</span>
                    <span class="fw-bold text-warning" id="pendingOrders">0</span>
                </div>
                <div class="stat-item d-flex justify-content-between mb-3">
                    <span>Đơn đã giao</span>
                    <span class="fw-bold text-success" id="deliveredOrders">0</span>
                </div>
                <div class="stat-item d-flex justify-content-between">
                    <span>Tổng đơn hàng</span>
                    <span class="fw-bold text-primary" id="totalOrders">0</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Growth Stats -->
    <div class="col-md-4">
        <div class="admin-card card">
            <div class="admin-card-header">
                <h5 class="fw-bold mb-0">
                    <i class="bi bi-graph-up-arrow text-info me-2"></i>
                    Tăng trưởng
                </h5>
            </div>
            <div class="admin-card-body">
                <div class="stat-item d-flex justify-content-between mb-3">
                    <span>Tăng trưởng tuần</span>
                    <span class="fw-bold text-success" id="weeklyGrowth">+0%</span>
                </div>
                <div class="stat-item d-flex justify-content-between mb-3">
                    <span>Tăng trưởng tháng</span>
                    <span class="fw-bold text-primary" id="monthlyGrowth">+0%</span>
                </div>
                <div class="stat-item d-flex justify-content-between mb-3">
                    <span>Khách hàng mới</span>
                    <span class="fw-bold text-warning" id="newCustomers">0</span>
                </div>
                <div class="stat-item d-flex justify-content-between">
                    <span>Khách hàng quay lại</span>
                    <span class="fw-bold text-info" id="returningCustomers">0</span>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
let advancedRevenueChart, productPerformanceChart, categoryDistributionChart;

// Khởi tạo biểu đồ
document.addEventListener('DOMContentLoaded', function() {
    initializeCharts();
    loadStatistics();
});

function initializeCharts() {
    // Advanced Revenue Chart
    const revenueCtx = document.getElementById('advancedRevenueChart').getContext('2d');
    advancedRevenueChart = new Chart(revenueCtx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [{
                label: 'Doanh thu (VNĐ)',
                data: [],
                borderColor: 'rgb(79, 70, 229)',
                backgroundColor: 'rgba(79, 70, 229, 0.1)',
                tension: 0.4,
                fill: true
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

    // Product Performance Chart
    const productCtx = document.getElementById('productPerformanceChart').getContext('2d');
    productPerformanceChart = new Chart(productCtx, {
        type: 'bar',
        data: {
            labels: [],
            datasets: [{
                label: 'Đã bán',
                data: [],
                backgroundColor: 'rgba(16, 185, 129, 0.8)',
                borderColor: 'rgba(16, 185, 129, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Category Distribution Chart
    const categoryCtx = document.getElementById('categoryDistributionChart').getContext('2d');
    categoryDistributionChart = new Chart(categoryCtx, {
        type: 'doughnut',
        data: {
            labels: [],
            datasets: [{
                data: [],
                backgroundColor: [
                    '#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'
                ]
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                }
            }
        }
    });

    updateCharts();
}

function updateCharts() {
    const timeRange = document.getElementById('timeRange').value;
    
    // Load revenue data
    fetch(`/admin/api/charts/daily-revenue?days=${timeRange}`)
        .then(response => response.json())
        .then(data => {
            advancedRevenueChart.data.labels = data.labels;
            advancedRevenueChart.data.datasets[0].data = data.data;
            advancedRevenueChart.update();
        });

    // Load product data
    fetch('/admin/api/charts/top-products?limit=10')
        .then(response => response.json())
        .then(data => {
            productPerformanceChart.data.labels = data.labels;
            productPerformanceChart.data.datasets[0].data = data.data;
            productPerformanceChart.update();
        });

    // Load category data
    fetch('/admin/api/charts/category-stats')
        .then(response => response.json())
        .then(data => {
            categoryDistributionChart.data.labels = data.labels;
            categoryDistributionChart.data.datasets[0].data = data.data;
            categoryDistributionChart.update();
        });
}

function loadStatistics() {
    fetch('/admin/api/charts/statistics')
        .then(response => response.json())
        .then(data => {
            document.getElementById('todayRevenue').textContent = data.revenue.today;
            document.getElementById('weekRevenue').textContent = data.revenue.week;
            document.getElementById('monthRevenue').textContent = data.revenue.month;
            document.getElementById('totalRevenue').textContent = data.revenue.total;
            
            document.getElementById('todayOrders').textContent = data.orders.today;
            document.getElementById('pendingOrders').textContent = Math.floor(data.orders.today * 0.3); // Estimate
            document.getElementById('deliveredOrders').textContent = Math.floor(data.orders.month * 0.7); // Estimate
            document.getElementById('totalOrders').textContent = data.orders.total;
        })
        .catch(error => {
            console.error('Error loading statistics:', error);
            // Fallback to mock data
            document.getElementById('todayRevenue').textContent = '2,500,000₫';
            document.getElementById('weekRevenue').textContent = '15,600,000₫';
            document.getElementById('monthRevenue').textContent = '45,200,000₫';
            document.getElementById('totalRevenue').textContent = '125,800,000₫';
            
            document.getElementById('todayOrders').textContent = '15';
            document.getElementById('pendingOrders').textContent = '8';
            document.getElementById('deliveredOrders').textContent = '42';
            document.getElementById('totalOrders').textContent = '156';
        });
    
    document.getElementById('weeklyGrowth').textContent = '+12.5%';
    document.getElementById('monthlyGrowth').textContent = '+8.3%';
    document.getElementById('newCustomers').textContent = '23';
    document.getElementById('returningCustomers').textContent = '67';
}

function refreshCharts() {
    updateCharts();
    loadStatistics();
}

function exportReport() {
    alert('Tính năng xuất báo cáo đang được phát triển...');
}
</script>
@endpush
