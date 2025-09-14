@extends('layouts.app')

@section('title', 'Fruit Variety Shop - Trái cây tươi ngon, chất lượng cao')

@section('content')
<!-- Hero Section -->
<div class="position-relative overflow-hidden">
    <div class="hero-bg position-absolute w-100 h-100" 
         style="background: linear-gradient(135deg, rgba(255, 107, 53, 0.9) 0%, rgba(229, 87, 34, 0.8) 100%), 
                url('https://images.unsplash.com/photo-1619566636858-adf3ef46400b?ixlib=rb-4.0.3&auto=format&fit=crop&w=2070&q=80') center/cover;"></div>
    
    <div class="position-relative py-5">
        <div class="container py-5">
            <div class="row align-items-center min-vh-75">
                <div class="col-lg-6">
                    <div class="hero-content text-white">
                        <div class="badge bg-white text-primary px-3 py-2 rounded-pill mb-4 fw-semibold">
                            <i class="bi bi-star-fill me-2"></i>Chất lượng #1 Việt Nam
                        </div>
                        
                        <h1 class="display-4 fw-bold mb-4 lh-1">
                            Hạt trái cây chất lượng <br>
                            <span class="text-warning">giao tận nhà</span>
                        </h1>
                        
                        <p class="fs-5 mb-5 opacity-90">
                            Khám phá bộ sưu tập hạt trái cây cao cấp từ những vùng trồng uy tín. 
                            Tươi ngon, sạch sẽ, giao hàng nhanh chóng trong ngày.
                        </p>
                        
                        <div class="d-flex flex-column flex-sm-row gap-3">
                            <a href="{{ route('products.index') }}" class="btn btn-warning btn-lg px-4 py-3">
                                <i class="bi bi-bag-check me-2"></i>Mua ngay
                            </a>
                            <a href="{{ route('categories.index') }}" class="btn btn-outline-light btn-lg px-4 py-3">
                                <i class="bi bi-grid-3x3-gap me-2"></i>Xem danh mục
                            </a>
                        </div>
                        
                        <div class="row mt-5">
                            <div class="col-4">
                                <div class="text-center">
                                    <div class="h3 fw-bold mb-1">{{ $totalProducts ?? 100 }}+</div>
                                    <small class="opacity-75">Sản phẩm</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="text-center">
                                    <div class="h3 fw-bold mb-1">24h</div>
                                    <small class="opacity-75">Giao hàng</small>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="text-center">
                                    <div class="h3 fw-bold mb-1">5⭐</div>
                                    <small class="opacity-75">Đánh giá</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Features Section -->
<section class="py-5 bg-white">
    <div class="container py-5">
        <div class="text-center mb-5">
            <div class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill mb-3">
                Tại sao chọn chúng tôi
            </div>
            <h2 class="display-6 fw-bold mb-3">Cam kết chất lượng hàng đầu</h2>
            <p class="text-muted fs-5">Chúng tôi luôn đặt chất lượng và sự hài lòng của khách hàng lên hàng đầu</p>
        </div>
        
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 text-center p-4 shadow-sm">
                    <div class="card-body">
                        <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-4" 
                             style="width: 80px; height: 80px;">
                            <i class="bi bi-truck text-primary fs-2"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Giao hàng nhanh chóng</h5>
                        <p class="text-muted mb-0">Giao hàng trong ngày tại Hà Nội, 2-3 ngày toàn quốc. Đảm bảo tươi ngon khi đến tay bạn.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 text-center p-4 shadow-sm">
                    <div class="card-body">
                        <div class="bg-secondary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-4" 
                             style="width: 80px; height: 80px;">
                            <i class="bi bi-shield-check text-secondary fs-2"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Chất lượng đảm bảo</h5>
                        <p class="text-muted mb-0">Tất cả sản phẩm đều được kiểm tra kỹ lưỡng, có nguồn gốc rõ ràng từ các vùng trồng uy tín.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 text-center p-4 shadow-sm">
                    <div class="card-body">
                        <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-4" 
                             style="width: 80px; height: 80px;">
                            <i class="bi bi-currency-exchange text-warning fs-2"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Giá cả hợp lý</h5>
                        <p class="text-muted mb-0">Cam kết giá tốt nhất thị trường. Chương trình khuyến mãi hấp dẫn thường xuyên.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 text-center p-4 shadow-sm">
                    <div class="card-body">
                        <div class="bg-info bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-4" 
                             style="width: 80px; height: 80px;">
                            <i class="bi bi-headset text-info fs-2"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Hỗ trợ 24/7</h5>
                        <p class="text-muted mb-0">Đội ngũ tư vấn chuyên nghiệp sẵn sàng hỗ trợ bạn mọi lúc, mọi nơi.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 text-center p-4 shadow-sm">
                    <div class="card-body">
                        <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-4" 
                             style="width: 80px; height: 80px;">
                            <i class="bi bi-arrow-repeat text-success fs-2"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Đổi trả dễ dàng</h5>
                        <p class="text-muted mb-0">Chính sách đổi trả trong 24h nếu không hài lòng về chất lượng sản phẩm.</p>
                    </div>
                </div>
            </div>
            
            <div class="col-lg-4 col-md-6">
                <div class="card h-100 border-0 text-center p-4 shadow-sm">
                    <div class="card-body">
                        <div class="bg-danger bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-4" 
                             style="width: 80px; height: 80px;">
                            <i class="bi bi-award text-danger fs-2"></i>
                        </div>
                        <h5 class="fw-bold mb-3">Chứng nhận chất lượng</h5>
                        <p class="text-muted mb-0">Được chứng nhận VietGAP, GlobalGAP và các tiêu chuẩn chất lượng quốc tế.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Recommended Products Section -->
@if(isset($recommendedProducts) && $recommendedProducts->count() > 0)
<section class="py-5 bg-white">
    <div class="container py-5">
        <div class="text-center mb-5">
            <div class="badge bg-success bg-opacity-10 text-success px-3 py-2 rounded-pill mb-3">
                <i class="bi bi-lightbulb me-1"></i>Dành riêng cho bạn
            </div>
            <h2 class="display-6 fw-bold mb-3">Sản phẩm được gợi ý</h2>
            <p class="text-muted fs-5">
                @auth
                    Dựa trên sở thích và lịch sử mua hàng của bạn
                @else
                    Những sản phẩm phổ biến và được yêu thích nhất
                @endauth
            </p>
        </div>
        
        <div class="row g-4" id="recommendedProductsContainer">
            @foreach($recommendedProducts as $product)
            <div class="col-lg-3 col-md-6">
                <div class="card product-card h-100 border-0 shadow-sm position-relative overflow-hidden">
                    @if($product->hasImage())
                        <div class="position-relative overflow-hidden">
                            <img src="{{ $product->image_url }}" 
                                 class="card-img-top object-fit-cover" 
                                 alt="{{ $product->name }}"
                                 style="height: 200px; transition: transform 0.3s ease;">
                            <div class="position-absolute top-0 start-0 m-3">
                                <span class="badge bg-success px-2 py-1 rounded-pill">
                                    <i class="bi bi-lightbulb-fill me-1"></i>Gợi ý
                                </span>
                            </div>
                            @auth
                            <div class="position-absolute top-0 end-0 m-3">
                                <button class="btn btn-sm btn-outline-light rounded-circle wishlist-btn" 
                                        data-product-id="{{ $product->id }}"
                                        title="Thêm vào danh sách yêu thích">
                                    <i class="bi bi-heart"></i>
                                </button>
                            </div>
                            @endauth
                        </div>
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center position-relative" 
                             style="height: 200px;">
                            <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                            <div class="position-absolute top-0 start-0 m-3">
                                <span class="badge bg-success px-2 py-1 rounded-pill">
                                    <i class="bi bi-lightbulb-fill me-1"></i>Gợi ý
                                </span>
                            </div>
                        </div>
                    @endif
                    
                    <div class="card-body d-flex flex-column p-3">
                        <div class="mb-2">
                            <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1 rounded-pill small">
                                {{ $product->category->name }}
                            </span>
                        </div>
                        
                        <h6 class="card-title fw-bold mb-2">{{ Str::limit($product->name, 50) }}</h6>
                        
                        @if($product->average_rating > 0)
                        <div class="mb-2">
                            <div class="d-flex align-items-center">
                                <div class="text-warning me-2">
                                    @for($i = 1; $i <= 5; $i++)
                                        @if($i <= floor($product->average_rating))
                                            <i class="bi bi-star-fill"></i>
                                        @elseif($i <= ceil($product->average_rating))
                                            <i class="bi bi-star-half"></i>
                                        @else
                                            <i class="bi bi-star"></i>
                                        @endif
                                    @endfor
                                </div>
                                <small class="text-muted">({{ $product->total_reviews ?? 0 }})</small>
                            </div>
                        </div>
                        @endif
                        
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="price-info">
                                <span class="h6 fw-bold text-primary mb-0">
                                    {{ number_format($product->price, 0, ',', '.') }}đ
                                </span>
                                <small class="text-muted d-block">/ gói</small>
                            </div>
                            <div class="text-muted">
                                @if($product->stock > 10)
                                    <span class="badge bg-success-subtle text-success">Còn hàng</span>
                                @elseif($product->stock > 0)
                                    <span class="badge bg-warning-subtle text-warning">Sắp hết</span>
                                @else
                                    <span class="badge bg-danger-subtle text-danger">Hết hàng</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="mt-auto">
                            <a href="{{ route('products.show', $product) }}" class="btn btn-outline-primary w-100 btn-sm">
                                <i class="bi bi-eye me-1"></i>Xem chi tiết
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="text-center mt-4">
            <button class="btn btn-outline-success" id="loadMoreRecommended" data-type="user" data-offset="8">
                <i class="bi bi-arrow-clockwise me-2"></i>Xem thêm gợi ý
            </button>
        </div>
    </div>
</section>
@endif

<!-- Popular & Trending Products Section -->
<section class="py-5" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
    <div class="container py-5">
        <!-- Popular Products -->
        @if(isset($popularProducts) && $popularProducts->count() > 0)
        <div class="mb-5">
            <div class="text-center mb-5">
                <div class="badge bg-warning bg-opacity-10 text-warning px-3 py-2 rounded-pill mb-3">
                    <i class="bi bi-fire me-1"></i>Phổ biến nhất
                </div>
                <h2 class="display-6 fw-bold mb-3">Sản phẩm bán chạy</h2>
                <p class="text-muted fs-5">Những sản phẩm được mua nhiều nhất trong thời gian gần đây</p>
            </div>
            
            <div class="row g-4" id="popularProductsContainer">
                @foreach($popularProducts as $product)
                <div class="col-lg-2 col-md-4 col-6">
                    <div class="card product-card h-100 border-0 shadow-sm position-relative overflow-hidden">
                        @if($product->hasImage())
                            <div class="position-relative overflow-hidden">
                                <img src="{{ $product->image_url }}" 
                                     class="card-img-top object-fit-cover" 
                                     alt="{{ $product->name }}"
                                     style="height: 150px; transition: transform 0.3s ease;">
                                <div class="position-absolute top-0 start-0 m-2">
                                    <span class="badge bg-warning px-2 py-1 rounded-pill">
                                        <i class="bi bi-fire me-1"></i>Hot
                                    </span>
                                </div>
                            </div>
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center position-relative" 
                                 style="height: 150px;">
                                <i class="bi bi-image text-muted" style="font-size: 1.5rem;"></i>
                                <div class="position-absolute top-0 start-0 m-2">
                                    <span class="badge bg-warning px-2 py-1 rounded-pill">
                                        <i class="bi bi-fire me-1"></i>Hot
                                    </span>
                                </div>
                            </div>
                        @endif
                        
                        <div class="card-body p-3">
                            <h6 class="card-title fw-bold mb-2 small">{{ Str::limit($product->name, 40) }}</h6>
                            <div class="text-primary fw-bold small">
                                {{ number_format($product->price, 0, ',', '.') }}đ
                            </div>
                            <a href="{{ route('products.show', $product) }}" class="btn btn-outline-primary w-100 btn-sm mt-2">
                                Xem
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
        
        <!-- Trending Products -->
        @if(isset($trendingProducts) && $trendingProducts->count() > 0)
        <div>
            <div class="text-center mb-5">
                <div class="badge bg-info bg-opacity-10 text-info px-3 py-2 rounded-pill mb-3">
                    <i class="bi bi-graph-up-arrow me-1"></i>Xu hướng
                </div>
                <h2 class="display-6 fw-bold mb-3">Sản phẩm đang thịnh hành</h2>
                <p class="text-muted fs-5">Những sản phẩm đang được quan tâm và tìm kiếm nhiều nhất</p>
            </div>
            
            <div class="row g-4" id="trendingProductsContainer">
                @foreach($trendingProducts as $product)
                <div class="col-lg-2 col-md-4 col-6">
                    <div class="card product-card h-100 border-0 shadow-sm position-relative overflow-hidden">
                        @if($product->hasImage())
                            <div class="position-relative overflow-hidden">
                                <img src="{{ $product->image_url }}" 
                                     class="card-img-top object-fit-cover" 
                                     alt="{{ $product->name }}"
                                     style="height: 150px; transition: transform 0.3s ease;">
                                <div class="position-absolute top-0 start-0 m-2">
                                    <span class="badge bg-info px-2 py-1 rounded-pill">
                                        <i class="bi bi-trending-up me-1"></i>Trending
                                    </span>
                                </div>
                            </div>
                        @else
                            <div class="card-img-top bg-light d-flex align-items-center justify-content-center position-relative" 
                                 style="height: 150px;">
                                <i class="bi bi-image text-muted" style="font-size: 1.5rem;"></i>
                                <div class="position-absolute top-0 start-0 m-2">
                                    <span class="badge bg-info px-2 py-1 rounded-pill">
                                        <i class="bi bi-trending-up me-1"></i>Trending
                                    </span>
                                </div>
                            </div>
                        @endif
                        
                        <div class="card-body p-3">
                            <h6 class="card-title fw-bold mb-2 small">{{ Str::limit($product->name, 40) }}</h6>
                            <div class="text-primary fw-bold small">
                                {{ number_format($product->price, 0, ',', '.') }}đ
                            </div>
                            <a href="{{ route('products.show', $product) }}" class="btn btn-outline-primary w-100 btn-sm mt-2">
                                Xem
                            </a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
    </div>
</section>

<!-- Featured Products Section -->
@if(isset($products) && $products->count() > 0)
<section class="py-5" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
    <div class="container py-5">
        <div class="text-center mb-5">
            <div class="badge bg-primary bg-opacity-10 text-primary px-3 py-2 rounded-pill mb-3">
                Sản phẩm nổi bật
            </div>
            <h2 class="display-6 fw-bold mb-3">Trái cây được yêu thích nhất</h2>
            <p class="text-muted fs-5">Những sản phẩm được khách hàng lựa chọn nhiều nhất</p>
        </div>
        
        <div class="row g-4">
            @foreach($products->take(6) as $product)
            <div class="col-lg-4 col-md-6">
                <div class="card product-card h-100 border-0 shadow-sm position-relative overflow-hidden">
                    @if($product->hasImage())
                        <div class="position-relative overflow-hidden">
                            <img src="{{ $product->image_url }}" 
                                 class="card-img-top object-fit-cover" 
                                 alt="{{ $product->name }}"
                                 style="height: 250px; transition: transform 0.3s ease;">
                            <div class="position-absolute top-0 end-0 m-3">
                                <span class="badge bg-primary px-3 py-2 rounded-pill">
                                    <i class="bi bi-star-fill me-1"></i>Nổi bật
                                </span>
                            </div>
                        </div>
                    @else
                        <div class="card-img-top bg-light d-flex align-items-center justify-content-center position-relative" 
                             style="height: 250px;">
                            <i class="bi bi-image text-muted" style="font-size: 3rem;"></i>
                            <div class="position-absolute top-0 end-0 m-3">
                                <span class="badge bg-primary px-3 py-2 rounded-pill">
                                    <i class="bi bi-star-fill me-1"></i>Nổi bật
                                </span>
                            </div>
                        </div>
                    @endif
                    
                    <div class="card-body d-flex flex-column p-4">
                        <div class="mb-2">
                            <span class="badge bg-secondary bg-opacity-10 text-secondary px-2 py-1 rounded-pill">
                                {{ $product->category->name }}
                            </span>
                        </div>
                        
                        <h5 class="card-title fw-bold mb-2">{{ $product->name }}</h5>
                        <p class="card-text text-muted mb-3 flex-grow-1">
                            {{ Str::limit($product->description ?? 'Sản phẩm chất lượng cao, tươi ngon', 100) }}
                        </p>
                        
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="price-info">
                                <span class="h5 fw-bold text-primary mb-0">
                                    {{ number_format($product->price, 0, ',', '.') }}đ
                                </span>
                                <small class="text-muted d-block">/ gói</small>
                            </div>
                            <div class="text-muted">
                                @if($product->stock > 10)
                                    <span class="badge bg-success">Còn hàng</span>
                                @elseif($product->stock > 0)
                                    <span class="badge bg-warning">Sắp hết</span>
                                @else
                                    <span class="badge bg-danger">Hết hàng</span>
                                @endif
                            </div>
                        </div>
                        
                        <div class="mt-auto">
                            <a href="{{ route('products.show', $product) }}" class="btn btn-primary w-100">
                                <i class="bi bi-eye me-2"></i>Xem chi tiết
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        
        <div class="text-center mt-5">
            <a href="{{ route('products.index') }}" class="btn btn-outline-primary btn-lg px-5">
                <i class="bi bi-arrow-right me-2"></i>Xem tất cả sản phẩm
            </a>
        </div>
    </div>
</section>
@else
<section class="py-5" style="background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);">
    <div class="container text-center py-5">
        <i class="bi bi-inbox display-1 text-muted mb-4"></i>
        <h3 class="text-muted mb-3">Chưa có sản phẩm nào</h3>
        <p class="text-muted mb-4">Hệ thống đang được cập nhật, vui lòng quay lại sau.</p>
        <a href="{{ route('categories.index') }}" class="btn btn-primary">
            <i class="bi bi-arrow-clockwise me-2"></i>Làm mới
        </a>
    </div>
</section>
@endif

<!-- CTA Section -->
<section class="py-5 position-relative overflow-hidden">
    <div class="position-absolute w-100 h-100" 
         style="background: linear-gradient(135deg, var(--secondary) 0%, var(--secondary-dark) 100%);"></div>
    
    <div class="position-relative py-5">
        <div class="container text-center text-white">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <h2 class="display-5 fw-bold mb-4">
                        Đặt hàng ngay hôm nay và tận hưởng cảm giác sắp có trái cây ngollll!!!
                    </h2>
                    <p class="fs-5 mb-5 opacity-90">
                        Đăng ký ngay hôm nay để nhận ưu đãi đặc biệt và cập nhật sản phẩm mới nhất
                    </p>
                    
                    <div class="d-flex flex-column flex-sm-row justify-content-center gap-3">
                        @guest
                            <a href="{{ route('register') }}" class="btn btn-warning btn-lg px-5">
                                <i class="bi bi-person-plus me-2"></i>Đăng ký ngay
                            </a>
                            <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg px-5">
                                <i class="bi bi-box-arrow-in-right me-2"></i>Đăng nhập
                            </a>
                        @else
                            <a href="{{ route('products.index') }}" class="btn btn-warning btn-lg px-5">
                                <i class="bi bi-bag-check me-2"></i>Mua sắm ngay
                            </a>
                        @endguest
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.product-card:hover img {
    transform: scale(1.05);
}

.product-card:hover {
    transform: translateY(-5px);
}

.min-vh-75 {
    min-height: 75vh;
}

@media (max-width: 768px) {
    .hero-content h1 {
        font-size: 2.5rem !important;
    }
    
    .min-vh-75 {
        min-height: 60vh;
    }
}
</style>
@endsection