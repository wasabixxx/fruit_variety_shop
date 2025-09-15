@extends('layouts.app')

@section('title', $page->meta_title)

@section('meta')
    <meta name="description" content="{{ $page->meta_description }}">
    @if($page->meta_keywords)
        <meta name="keywords" content="{{ $page->meta_keywords }}">
    @endif
@endsection

@section('content')
<!-- Hero Section -->
<div class="hero-section bg-primary text-white py-5">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-6">
                <h1 class="display-4 fw-bold mb-3">{{ $page->title }}</h1>
                @if($page->excerpt)
                    <p class="lead mb-4">{{ $page->excerpt }}</p>
                @endif
            </div>
            <div class="col-lg-6">
                @if($page->featured_image)
                    <img src="{{ $page->featured_image }}" 
                         alt="{{ $page->title }}" 
                         class="img-fluid rounded shadow">
                @else
                    <div class="text-center">
                        <i class="bi bi-people-fill" style="font-size: 8rem; opacity: 0.3;"></i>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Breadcrumb -->
<div class="container py-3">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $page->title }}</li>
        </ol>
    </nav>
</div>

<!-- Main Content -->
<div class="container py-5">
    <div class="row">
        <div class="col-lg-8 mx-auto">
            <!-- Page Content -->
            <div class="content-wrapper">
                {!! $page->content !!}
            </div>

            <!-- Company Stats (if applicable) -->
            <div class="stats-section my-5 p-4 bg-light rounded">
                <div class="row text-center">
                    <div class="col-md-3 col-6 mb-3">
                        <div class="stat-item">
                            <h3 class="text-primary mb-1">5+</h3>
                            <p class="mb-0 text-muted">Năm kinh nghiệm</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="stat-item">
                            <h3 class="text-primary mb-1">1000+</h3>
                            <p class="mb-0 text-muted">Khách hàng hài lòng</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="stat-item">
                            <h3 class="text-primary mb-1">50+</h3>
                            <p class="mb-0 text-muted">Loại trái cây</p>
                        </div>
                    </div>
                    <div class="col-md-3 col-6 mb-3">
                        <div class="stat-item">
                            <h3 class="text-primary mb-1">24/7</h3>
                            <p class="mb-0 text-muted">Hỗ trợ khách hàng</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Our Values Section -->
            <div class="values-section my-5">
                <h2 class="text-center mb-5">Giá trị cốt lõi</h2>
                <div class="row">
                    <div class="col-md-4 mb-4">
                        <div class="value-card text-center p-4 h-100 border rounded">
                            <div class="value-icon mb-3">
                                <i class="bi bi-shield-check text-primary" style="font-size: 3rem;"></i>
                            </div>
                            <h4>Chất lượng</h4>
                            <p class="text-muted">Cam kết cung cấp những sản phẩm trái cây tươi ngon, chất lượng cao nhất.</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="value-card text-center p-4 h-100 border rounded">
                            <div class="value-icon mb-3">
                                <i class="bi bi-heart text-danger" style="font-size: 3rem;"></i>
                            </div>
                            <h4>Tận tâm</h4>
                            <p class="text-muted">Phục vụ khách hàng bằng tất cả sự tận tâm và chu đáo nhất.</p>
                        </div>
                    </div>
                    <div class="col-md-4 mb-4">
                        <div class="value-card text-center p-4 h-100 border rounded">
                            <div class="value-icon mb-3">
                                <i class="bi bi-lightning text-warning" style="font-size: 3rem;"></i>
                            </div>
                            <h4>Nhanh chóng</h4>
                            <p class="text-muted">Giao hàng nhanh chóng, đảm bảo độ tươi ngon của sản phẩm.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Call to Action -->
            <div class="cta-section text-center my-5 p-5 bg-primary text-white rounded">
                <h3 class="mb-3">Bắt đầu mua sắm ngay!</h3>
                <p class="mb-4">Khám phá bộ sưu tập trái cây tươi ngon của chúng tôi</p>
                <a href="{{ route('products.index') }}" class="btn btn-light btn-lg">
                    <i class="bi bi-basket2 me-2"></i>Xem sản phẩm
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Contact Info Section -->
<div class="contact-info-section bg-light py-5">
    <div class="container">
        <div class="row">
            <div class="col-md-4 text-center mb-4">
                <div class="contact-item">
                    <i class="bi bi-geo-alt text-primary mb-3" style="font-size: 2rem;"></i>
                    <h5>Địa chỉ</h5>
                    <p class="text-muted">123 Đường ABC, Quận XYZ<br>TP. Hồ Chí Minh</p>
                </div>
            </div>
            <div class="col-md-4 text-center mb-4">
                <div class="contact-item">
                    <i class="bi bi-telephone text-primary mb-3" style="font-size: 2rem;"></i>
                    <h5>Điện thoại</h5>
                    <p class="text-muted">
                        <a href="tel:0123456789" class="text-decoration-none">0123 456 789</a><br>
                        <a href="tel:0987654321" class="text-decoration-none">0987 654 321</a>
                    </p>
                </div>
            </div>
            <div class="col-md-4 text-center mb-4">
                <div class="contact-item">
                    <i class="bi bi-envelope text-primary mb-3" style="font-size: 2rem;"></i>
                    <h5>Email</h5>
                    <p class="text-muted">
                        <a href="mailto:info@fruitshop.com" class="text-decoration-none">info@fruitshop.com</a><br>
                        <a href="mailto:support@fruitshop.com" class="text-decoration-none">support@fruitshop.com</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.hero-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.content-wrapper {
    font-size: 1.1rem;
    line-height: 1.8;
}

.content-wrapper h1,
.content-wrapper h2,
.content-wrapper h3,
.content-wrapper h4,
.content-wrapper h5,
.content-wrapper h6 {
    color: #2c3e50;
    margin-top: 2rem;
    margin-bottom: 1rem;
}

.content-wrapper p {
    margin-bottom: 1.5rem;
    text-align: justify;
}

.content-wrapper img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    margin: 1rem 0;
}

.stats-section .stat-item h3 {
    font-size: 2.5rem;
    font-weight: bold;
}

.value-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.value-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.1);
}

.cta-section {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.contact-item {
    transition: transform 0.3s ease;
}

.contact-item:hover {
    transform: scale(1.05);
}

.contact-item a {
    color: inherit;
}

.contact-item a:hover {
    color: #667eea;
}

@media (max-width: 768px) {
    .hero-section h1 {
        font-size: 2rem;
    }
    
    .stats-section .stat-item h3 {
        font-size: 2rem;
    }
}
</style>
@endpush