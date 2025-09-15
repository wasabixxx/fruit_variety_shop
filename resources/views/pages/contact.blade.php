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
        <div class="text-center">
            <h1 class="display-4 fw-bold mb-3">{{ $page->title }}</h1>
            @if($page->excerpt)
                <p class="lead">{{ $page->excerpt }}</p>
            @endif
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

<div class="container py-5">
    <div class="row">
        <!-- Contact Form -->
        <div class="col-lg-8">
            <!-- Page Content -->
            <div class="content-wrapper mb-5">
                {!! $page->content !!}
            </div>

            <!-- Contact Form -->
            <div class="contact-form-section">
                <h2 class="mb-4">Gửi tin nhắn cho chúng tôi</h2>
                
                @if(session('contact_success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="bi bi-check-circle me-2"></i>
                        {{ session('contact_success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                @endif

                <form action="{{ route('pages.contact.submit') }}" method="POST" class="contact-form">
                    @csrf
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Họ và tên <span class="text-danger">*</span></label>
                            <input type="text" 
                                   class="form-control @error('name') is-invalid @enderror" 
                                   id="name" 
                                   name="name" 
                                   value="{{ old('name') }}" 
                                   required
                                   placeholder="Nhập họ và tên của bạn">
                            @error('name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="email" class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" 
                                   class="form-control @error('email') is-invalid @enderror" 
                                   id="email" 
                                   name="email" 
                                   value="{{ old('email') }}" 
                                   required
                                   placeholder="Nhập địa chỉ email">
                            @error('email')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="phone" class="form-label">Số điện thoại</label>
                            <input type="tel" 
                                   class="form-control @error('phone') is-invalid @enderror" 
                                   id="phone" 
                                   name="phone" 
                                   value="{{ old('phone') }}"
                                   placeholder="Nhập số điện thoại">
                            @error('phone')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                        
                        <div class="col-md-6 mb-3">
                            <label for="subject" class="form-label">Chủ đề <span class="text-danger">*</span></label>
                            <select class="form-select @error('subject') is-invalid @enderror" 
                                    id="subject" 
                                    name="subject" 
                                    required>
                                <option value="">Chọn chủ đề</option>
                                <option value="general" {{ old('subject') == 'general' ? 'selected' : '' }}>Thông tin chung</option>
                                <option value="order" {{ old('subject') == 'order' ? 'selected' : '' }}>Đặt hàng</option>
                                <option value="support" {{ old('subject') == 'support' ? 'selected' : '' }}>Hỗ trợ kỹ thuật</option>
                                <option value="complaint" {{ old('subject') == 'complaint' ? 'selected' : '' }}>Khiếu nại</option>
                                <option value="suggestion" {{ old('subject') == 'suggestion' ? 'selected' : '' }}>Góp ý</option>
                                <option value="other" {{ old('subject') == 'other' ? 'selected' : '' }}>Khác</option>
                            </select>
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="message" class="form-label">Tin nhắn <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('message') is-invalid @enderror" 
                                  id="message" 
                                  name="message" 
                                  rows="6" 
                                  required
                                  placeholder="Nhập nội dung tin nhắn...">{{ old('message') }}</textarea>
                        @error('message')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input @error('terms') is-invalid @enderror" 
                                   type="checkbox" 
                                   id="terms" 
                                   name="terms" 
                                   value="1" 
                                   {{ old('terms') ? 'checked' : '' }}
                                   required>
                            <label class="form-check-label" for="terms">
                                Tôi đồng ý với <a href="{{ route('pages.show', 'chinh-sach-bao-mat') }}" target="_blank">chính sách bảo mật</a> 
                                và <a href="{{ route('pages.show', 'dieu-khoan-su-dung') }}" target="_blank">điều khoản sử dụng</a>
                                <span class="text-danger">*</span>
                            </label>
                            @error('terms')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary btn-lg">
                        <i class="bi bi-send me-2"></i>Gửi tin nhắn
                    </button>
                </form>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="col-lg-4">
            <div class="contact-info-sidebar">
                <!-- Contact Cards -->
                <div class="contact-card mb-4 p-4 bg-light rounded">
                    <h4 class="mb-3">Thông tin liên hệ</h4>
                    
                    <div class="contact-item mb-3">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-geo-alt text-primary me-3 mt-1" style="font-size: 1.2rem;"></i>
                            <div>
                                <h6 class="mb-1">Địa chỉ</h6>
                                <p class="text-muted mb-0">
                                    123 Đường ABC, Quận XYZ<br>
                                    TP. Hồ Chí Minh, Việt Nam
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="contact-item mb-3">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-telephone text-primary me-3 mt-1" style="font-size: 1.2rem;"></i>
                            <div>
                                <h6 class="mb-1">Điện thoại</h6>
                                <p class="text-muted mb-0">
                                    <a href="tel:0123456789" class="text-decoration-none">0123 456 789</a><br>
                                    <a href="tel:0987654321" class="text-decoration-none">0987 654 321</a>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="contact-item mb-3">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-envelope text-primary me-3 mt-1" style="font-size: 1.2rem;"></i>
                            <div>
                                <h6 class="mb-1">Email</h6>
                                <p class="text-muted mb-0">
                                    <a href="mailto:info@fruitshop.com" class="text-decoration-none">info@fruitshop.com</a><br>
                                    <a href="mailto:support@fruitshop.com" class="text-decoration-none">support@fruitshop.com</a>
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="contact-item">
                        <div class="d-flex align-items-start">
                            <i class="bi bi-clock text-primary me-3 mt-1" style="font-size: 1.2rem;"></i>
                            <div>
                                <h6 class="mb-1">Giờ làm việc</h6>
                                <p class="text-muted mb-0">
                                    Thứ 2 - Thứ 6: 8:00 - 18:00<br>
                                    Thứ 7 - CN: 9:00 - 17:00
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Social Media -->
                <div class="social-media-card mb-4 p-4 bg-primary text-white rounded">
                    <h4 class="mb-3">Kết nối với chúng tôi</h4>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-white" style="font-size: 1.5rem;">
                            <i class="bi bi-facebook"></i>
                        </a>
                        <a href="#" class="text-white" style="font-size: 1.5rem;">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a href="#" class="text-white" style="font-size: 1.5rem;">
                            <i class="bi bi-twitter"></i>
                        </a>
                        <a href="#" class="text-white" style="font-size: 1.5rem;">
                            <i class="bi bi-youtube"></i>
                        </a>
                    </div>
                </div>

                <!-- FAQ Link -->
                <div class="faq-card p-4 border rounded">
                    <h5 class="mb-3">Câu hỏi thường gặp</h5>
                    <p class="text-muted mb-3">Tìm câu trả lời cho những câu hỏi phổ biến nhất.</p>
                    <a href="{{ route('pages.show', 'cau-hoi-thuong-gap') }}" class="btn btn-outline-primary">
                        Xem FAQ
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Map Section (Optional) -->
<div class="map-section py-5 bg-light">
    <div class="container">
        <h2 class="text-center mb-4">Vị trí của chúng tôi</h2>
        <div class="map-container">
            <!-- Replace with actual Google Maps embed code -->
            <div class="ratio ratio-21x9">
                <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3919.3244587688985!2d106.6621538!3d10.7796739!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x31752ee09c7bfe61%3A0x4b2bda2b79b69c8a!2zSOG7kyBDaMOtIE1pbmggQ2l0eSwgVmnhu4d0IE5hbQ!5e0!3m2!1sen!2s!4v1672000000000!5m2!1sen!2s" 
                        style="border:0;" 
                        allowfullscreen="" 
                        loading="lazy" 
                        referrerpolicy="no-referrer-when-downgrade"></iframe>
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
}

.contact-form {
    background: #f8f9fa;
    padding: 2rem;
    border-radius: 10px;
    border: 1px solid #e9ecef;
}

.contact-form .form-control,
.contact-form .form-select {
    border-radius: 8px;
    border: 1px solid #ced4da;
    padding: 0.75rem 1rem;
    font-size: 1rem;
}

.contact-form .form-control:focus,
.contact-form .form-select:focus {
    border-color: #667eea;
    box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.contact-form label {
    font-weight: 600;
    color: #2c3e50;
}

.contact-card {
    border: 1px solid #e9ecef;
}

.contact-item a {
    color: inherit;
    transition: color 0.3s ease;
}

.contact-item a:hover {
    color: #667eea;
}

.social-media-card {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.social-media-card a {
    transition: transform 0.3s ease, opacity 0.3s ease;
}

.social-media-card a:hover {
    transform: scale(1.2);
    opacity: 0.8;
}

.faq-card {
    transition: box-shadow 0.3s ease;
}

.faq-card:hover {
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.map-container {
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
}

@media (max-width: 768px) {
    .contact-form {
        padding: 1.5rem;
    }
    
    .hero-section h1 {
        font-size: 2rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
// Form validation and enhancement
document.addEventListener('DOMContentLoaded', function() {
    const form = document.querySelector('.contact-form');
    const submitBtn = form.querySelector('button[type="submit"]');
    
    form.addEventListener('submit', function(e) {
        // Disable submit button to prevent double submission
        submitBtn.disabled = true;
        submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Đang gửi...';
        
        // Re-enable button after 3 seconds (in case form submission fails)
        setTimeout(() => {
            submitBtn.disabled = false;
            submitBtn.innerHTML = '<i class="bi bi-send me-2"></i>Gửi tin nhắn';
        }, 3000);
    });
    
    // Auto-resize textarea
    const messageTextarea = document.getElementById('message');
    messageTextarea.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });
});
</script>
@endpush