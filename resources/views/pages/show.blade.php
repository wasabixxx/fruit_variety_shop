@extends('layouts.app')

@section('title', $page->meta_title)

@section('meta')
    <meta name="description" content="{{ $page->meta_description }}">
    @if($page->meta_keywords)
        <meta name="keywords" content="{{ $page->meta_keywords }}">
    @endif
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ request()->url() }}">
    <meta property="og:title" content="{{ $page->meta_title }}">
    <meta property="og:description" content="{{ $page->meta_description }}">
    @if($page->featured_image)
        <meta property="og:image" content="{{ $page->featured_image }}">
    @endif
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="{{ request()->url() }}">
    <meta property="twitter:title" content="{{ $page->meta_title }}">
    <meta property="twitter:description" content="{{ $page->meta_description }}">
    @if($page->featured_image)
        <meta property="twitter:image" content="{{ $page->featured_image }}">
    @endif
@endsection

@section('content')
<div class="container-fluid py-5">
    <div class="container">
        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('home') }}">Trang chủ</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $page->title }}</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-lg-8 mx-auto">
                <!-- Page Header -->
                <div class="text-center mb-5">
                    @if($page->featured_image)
                        <div class="mb-4">
                            <img src="{{ $page->featured_image }}" 
                                 alt="{{ $page->title }}" 
                                 class="img-fluid rounded shadow">
                        </div>
                    @endif
                    
                    <h1 class="display-4 fw-bold text-primary mb-3">{{ $page->title }}</h1>
                    
                    @if($page->excerpt)
                        <p class="lead text-muted">{{ $page->excerpt }}</p>
                    @endif
                    
                    <div class="text-muted small">
                        <i class="bi bi-calendar-event me-1"></i>
                        Cập nhật: {{ $page->updated_at->format('d/m/Y') }}
                    </div>
                </div>

                <!-- Page Content -->
                <div class="content-wrapper">
                    <div class="page-content">
                        {!! $page->content !!}
                    </div>
                </div>

                <!-- Share Buttons -->
                <div class="share-section mt-5 pt-4 border-top">
                    <h5 class="mb-3">Chia sẻ trang này:</h5>
                    <div class="d-flex gap-2">
                        <a href="https://www.facebook.com/sharer/sharer.php?u={{ urlencode(request()->url()) }}" 
                           target="_blank" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-facebook me-1"></i> Facebook
                        </a>
                        <a href="https://twitter.com/intent/tweet?url={{ urlencode(request()->url()) }}&text={{ urlencode($page->title) }}" 
                           target="_blank" class="btn btn-outline-info btn-sm">
                            <i class="bi bi-twitter me-1"></i> Twitter
                        </a>
                        <button type="button" class="btn btn-outline-secondary btn-sm" onclick="copyPageUrl()">
                            <i class="bi bi-link-45deg me-1"></i> Copy Link
                        </button>
                    </div>
                </div>

                <!-- Back to Home -->
                <div class="text-center mt-5">
                    <a href="{{ route('home') }}" class="btn btn-primary">
                        <i class="bi bi-house me-1"></i> Về trang chủ
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.page-content {
    font-size: 1.1rem;
    line-height: 1.8;
    color: #333;
}

.page-content h1,
.page-content h2,
.page-content h3,
.page-content h4,
.page-content h5,
.page-content h6 {
    color: #2c3e50;
    margin-top: 2rem;
    margin-bottom: 1rem;
}

.page-content h1 {
    font-size: 2.5rem;
    border-bottom: 3px solid #3498db;
    padding-bottom: 0.5rem;
}

.page-content h2 {
    font-size: 2rem;
    color: #3498db;
}

.page-content h3 {
    font-size: 1.5rem;
}

.page-content p {
    margin-bottom: 1.5rem;
    text-align: justify;
}

.page-content img {
    max-width: 100%;
    height: auto;
    border-radius: 8px;
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    margin: 1rem 0;
}

.page-content ul,
.page-content ol {
    margin-bottom: 1.5rem;
    padding-left: 2rem;
}

.page-content li {
    margin-bottom: 0.5rem;
}

.page-content blockquote {
    border-left: 4px solid #3498db;
    background-color: #f8f9fa;
    padding: 1rem 1.5rem;
    margin: 2rem 0;
    font-style: italic;
    border-radius: 0 8px 8px 0;
}

.page-content table {
    width: 100%;
    border-collapse: collapse;
    margin: 2rem 0;
}

.page-content table th,
.page-content table td {
    border: 1px solid #dee2e6;
    padding: 0.75rem;
    text-align: left;
}

.page-content table th {
    background-color: #f8f9fa;
    font-weight: 600;
}

.page-content a {
    color: #3498db;
    text-decoration: none;
}

.page-content a:hover {
    color: #2980b9;
    text-decoration: underline;
}

.share-section .btn {
    border-radius: 20px;
}

@media (max-width: 768px) {
    .page-content {
        font-size: 1rem;
    }
    
    .page-content h1 {
        font-size: 2rem;
    }
    
    .page-content h2 {
        font-size: 1.5rem;
    }
}
</style>
@endpush

@push('scripts')
<script>
function copyPageUrl() {
    const url = window.location.href;
    
    if (navigator.clipboard) {
        navigator.clipboard.writeText(url).then(function() {
            showToast('Đã copy link trang!', 'success');
        });
    } else {
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = url;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        showToast('Đã copy link trang!', 'success');
    }
}

function showToast(message, type = 'info') {
    // Simple toast notification
    const toast = document.createElement('div');
    toast.className = `alert alert-${type === 'success' ? 'success' : 'info'} position-fixed`;
    toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 250px;';
    toast.innerHTML = `
        <div class="d-flex align-items-center">
            <i class="bi bi-check-circle me-2"></i>
            ${message}
            <button type="button" class="btn-close ms-auto" onclick="this.parentElement.parentElement.remove()"></button>
        </div>
    `;
    
    document.body.appendChild(toast);
    
    // Auto remove after 3 seconds
    setTimeout(() => {
        if (toast.parentElement) {
            toast.remove();
        }
    }, 3000);
}
</script>
@endpush