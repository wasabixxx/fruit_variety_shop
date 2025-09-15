@extends('layouts.app')

@section('title', $page->meta_title)

@section('meta')
    <meta name="description" content="{{ $page->meta_description }}">
    @if($page->meta_keywords)
        <meta name="keywords" content="{{ $page->meta_keywords }}">
    @endif
@endsection

@section('content')
<!-- Full Width Hero Section -->
@if($page->featured_image)
<div class="hero-banner position-relative overflow-hidden">
    <div class="hero-image">
        <img src="{{ $page->featured_image }}" alt="{{ $page->title }}" class="w-100" style="height: 60vh; object-fit: cover;">
    </div>
    <div class="hero-overlay position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center">
        <div class="hero-content text-center text-white">
            <h1 class="display-3 fw-bold mb-3 text-shadow">{{ $page->title }}</h1>
            @if($page->excerpt)
                <p class="lead mb-4 text-shadow">{{ $page->excerpt }}</p>
            @endif
            <button onclick="scrollToContent()" class="btn btn-light btn-lg rounded-pill">
                <i class="bi bi-arrow-down me-2"></i>Khám phá ngay
            </button>
        </div>
    </div>
</div>
@else
<!-- Alternative Hero without image -->
<div class="hero-section bg-gradient py-5">
    <div class="container-fluid">
        <div class="row justify-content-center">
            <div class="col-lg-8 text-center">
                <h1 class="display-3 fw-bold text-white mb-3">{{ $page->title }}</h1>
                @if($page->excerpt)
                    <p class="lead text-white-50 mb-4">{{ $page->excerpt }}</p>
                @endif
            </div>
        </div>
    </div>
</div>
@endif

<!-- Breadcrumb -->
<div class="container-fluid py-3 bg-light">
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $page->title }}</li>
            </ol>
        </nav>
    </div>
</div>

<!-- Main Content - Full Width -->
<div id="main-content" class="main-content py-5">
    <div class="container-fluid">
        <div class="content-wrapper">
            {!! $page->content !!}
        </div>
    </div>
</div>

<!-- Action Bar -->
<div class="action-bar bg-primary text-white py-4">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-md-8">
                <h4 class="mb-2">Bạn có thắc mắc gì không?</h4>
                <p class="mb-md-0">Liên hệ với chúng tôi để được hỗ trợ tốt nhất</p>
            </div>
            <div class="col-md-4 text-md-end">
                <a href="{{ route('pages.show', 'lien-he') }}" class="btn btn-light btn-lg">
                    <i class="bi bi-telephone me-2"></i>Liên hệ ngay
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Back to Top Button -->
<button id="backToTop" class="btn btn-primary position-fixed rounded-circle shadow" 
        style="bottom: 20px; right: 20px; width: 50px; height: 50px; display: none; z-index: 1000;">
    <i class="bi bi-arrow-up"></i>
</button>
@endsection

@push('styles')
<style>
/* Hero Section Styles */
.hero-overlay {
    background: linear-gradient(45deg, rgba(0,0,0,0.6) 0%, rgba(0,0,0,0.3) 100%);
}

.text-shadow {
    text-shadow: 2px 2px 4px rgba(0,0,0,0.5);
}

.bg-gradient {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

/* Content Wrapper Styles */
.content-wrapper {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #333;
}

.content-wrapper h1,
.content-wrapper h2,
.content-wrapper h3,
.content-wrapper h4,
.content-wrapper h5,
.content-wrapper h6 {
    color: #2c3e50;
    margin: 3rem 0 1.5rem 0;
    padding-bottom: 0.5rem;
    border-bottom: 2px solid #e9ecef;
}

.content-wrapper h1 {
    font-size: 3rem;
    color: #3498db;
    border-bottom: 3px solid #3498db;
    text-align: center;
    margin-bottom: 2rem;
}

.content-wrapper h2 {
    font-size: 2.5rem;
    color: #2980b9;
}

.content-wrapper h3 {
    font-size: 2rem;
}

.content-wrapper h4 {
    font-size: 1.5rem;
}

.content-wrapper p {
    margin-bottom: 2rem;
    text-align: justify;
}

.content-wrapper img {
    max-width: 100%;
    height: auto;
    border-radius: 10px;
    box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    margin: 2rem auto;
    display: block;
}

.content-wrapper ul,
.content-wrapper ol {
    margin-bottom: 2rem;
    padding-left: 2.5rem;
}

.content-wrapper li {
    margin-bottom: 0.75rem;
}

.content-wrapper blockquote {
    border-left: 5px solid #3498db;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
    padding: 2rem 2.5rem;
    margin: 3rem 0;
    font-size: 1.2rem;
    font-style: italic;
    border-radius: 0 15px 15px 0;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.content-wrapper table {
    width: 100%;
    border-collapse: collapse;
    margin: 2rem 0;
    border-radius: 10px;
    overflow: hidden;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.content-wrapper table th,
.content-wrapper table td {
    border: 1px solid #dee2e6;
    padding: 1rem;
    text-align: left;
}

.content-wrapper table th {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
    font-weight: 600;
}

.content-wrapper table tr:nth-child(even) {
    background-color: #f8f9fa;
}

.content-wrapper table tr:hover {
    background-color: #e3f2fd;
}

.content-wrapper a {
    color: #3498db;
    text-decoration: none;
    transition: all 0.3s ease;
}

.content-wrapper a:hover {
    color: #2980b9;
    text-decoration: underline;
    text-shadow: 1px 1px 2px rgba(0,0,0,0.1);
}

/* Special Full-Width Sections */
.content-wrapper .full-width-section {
    margin: 3rem -15px;
    padding: 4rem 15px;
    background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
}

.content-wrapper .highlight-box {
    background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
    border: 2px solid #2196f3;
    border-radius: 15px;
    padding: 2rem;
    margin: 2rem 0;
    box-shadow: 0 10px 25px rgba(33, 150, 243, 0.1);
}

.content-wrapper .warning-box {
    background: linear-gradient(135deg, #fff3e0 0%, #ffe0b2 100%);
    border: 2px solid #ff9800;
    border-radius: 15px;
    padding: 2rem;
    margin: 2rem 0;
    box-shadow: 0 10px 25px rgba(255, 152, 0, 0.1);
}

.content-wrapper .success-box {
    background: linear-gradient(135deg, #e8f5e8 0%, #c8e6c9 100%);
    border: 2px solid #4caf50;
    border-radius: 15px;
    padding: 2rem;
    margin: 2rem 0;
    box-shadow: 0 10px 25px rgba(76, 175, 80, 0.1);
}

/* Action Bar */
.action-bar {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

/* Back to Top Button */
#backToTop {
    transition: all 0.3s ease;
    opacity: 0;
    transform: translateY(100px);
}

#backToTop.show {
    opacity: 1;
    transform: translateY(0);
}

#backToTop:hover {
    transform: translateY(-5px);
    box-shadow: 0 10px 25px rgba(0,0,0,0.2);
}

/* Responsive Design */
@media (max-width: 1200px) {
    .container-fluid {
        padding-left: 2rem;
        padding-right: 2rem;
    }
}

@media (max-width: 768px) {
    .hero-banner .hero-content h1 {
        font-size: 2.5rem;
    }
    
    .content-wrapper {
        font-size: 1rem;
    }
    
    .content-wrapper h1 {
        font-size: 2rem;
    }
    
    .content-wrapper h2 {
        font-size: 1.75rem;
    }
    
    .content-wrapper h3 {
        font-size: 1.5rem;
    }
    
    .content-wrapper blockquote {
        padding: 1.5rem;
        font-size: 1.1rem;
    }
    
    .container-fluid {
        padding-left: 1rem;
        padding-right: 1rem;
    }
    
    .content-wrapper .full-width-section {
        margin: 2rem -1rem;
        padding: 2rem 1rem;
    }
}

@media (max-width: 576px) {
    .hero-banner .hero-content h1 {
        font-size: 2rem;
    }
    
    .content-wrapper h1 {
        font-size: 1.75rem;
    }
    
    .action-bar .row {
        text-align: center;
    }
    
    .action-bar .col-md-4 {
        margin-top: 1rem;
    }
}

/* Animation Effects */
@keyframes fadeInUp {
    from {
        opacity: 0;
        transform: translateY(30px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.content-wrapper > * {
    animation: fadeInUp 0.6s ease-out;
}

/* Print Styles */
@media print {
    .hero-banner,
    .action-bar,
    #backToTop,
    nav {
        display: none !important;
    }
    
    .content-wrapper {
        font-size: 12pt;
        line-height: 1.5;
    }
    
    .content-wrapper h1,
    .content-wrapper h2,
    .content-wrapper h3 {
        page-break-after: avoid;
    }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Back to top button functionality
    const backToTopButton = document.getElementById('backToTop');
    
    window.addEventListener('scroll', function() {
        if (window.pageYOffset > 300) {
            backToTopButton.classList.add('show');
            backToTopButton.style.display = 'block';
        } else {
            backToTopButton.classList.remove('show');
            setTimeout(() => {
                if (!backToTopButton.classList.contains('show')) {
                    backToTopButton.style.display = 'none';
                }
            }, 300);
        }
    });
    
    backToTopButton.addEventListener('click', function(e) {
        e.preventDefault();
        window.scrollTo({
            top: 0,
            behavior: 'smooth'
        });
    });
    
    // Smooth scroll for internal links
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            e.preventDefault();
            const target = document.querySelector(this.getAttribute('href'));
            if (target) {
                target.scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });
    });
    
    // Auto-hide header on scroll down, show on scroll up
    let lastScrollTop = 0;
    const header = document.querySelector('.navbar');
    
    window.addEventListener('scroll', function() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        if (scrollTop > lastScrollTop && scrollTop > 100) {
            // Scrolling down
            if (header) {
                header.style.transform = 'translateY(-100%)';
            }
        } else {
            // Scrolling up
            if (header) {
                header.style.transform = 'translateY(0)';
            }
        }
        
        lastScrollTop = scrollTop;
    });
    
    // Add transitions to header
    if (header) {
        header.style.transition = 'transform 0.3s ease-in-out';
    }
});

function scrollToContent() {
    const content = document.getElementById('main-content');
    if (content) {
        content.scrollIntoView({
            behavior: 'smooth',
            block: 'start'
        });
    }
}

// Lazy loading for images
if ('IntersectionObserver' in window) {
    const imageObserver = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const img = entry.target;
                img.src = img.dataset.src;
                img.classList.remove('lazy');
                imageObserver.unobserve(img);
            }
        });
    });
    
    document.querySelectorAll('img[data-src]').forEach(img => {
        imageObserver.observe(img);
    });
}

// Page performance tracking
window.addEventListener('load', function() {
    // Optional: Add analytics or performance tracking here
    console.log('Page fully loaded');
});
</script>
@endpush