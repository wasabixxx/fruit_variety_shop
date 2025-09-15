@extends('admin.layout')

@section('title', 'Chi tiết trang: ' . $page->title)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-2">Chi tiết trang</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.pages.index') }}">Quản lý trang</a></li>
                <li class="breadcrumb-item active">{{ $page->title }}</li>
            </ol>
        </nav>
    </div>
    <div class="btn-group">
        <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-primary">
            <i class="bi bi-pencil"></i> Chỉnh sửa
        </a>
        <a href="{{ route('pages.show', $page->slug) }}" target="_blank" class="btn btn-outline-info">
            <i class="bi bi-eye"></i> Xem trang
        </a>
        <a href="{{ route('admin.pages.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

<!-- Status Alerts -->
<div class="row mb-4">
    <div class="col-12">
        @if($page->is_published)
            <div class="alert alert-success">
                <i class="bi bi-check-circle"></i>
                <strong>Trang đã được xuất bản</strong> và có thể truy cập tại: 
                <a href="{{ route('pages.show', $page->slug) }}" target="_blank" class="alert-link">
                    {{ route('pages.show', $page->slug) }}
                </a>
            </div>
        @else
            <div class="alert alert-warning">
                <i class="bi bi-exclamation-triangle"></i>
                <strong>Trang chưa được xuất bản.</strong> Trang này không hiển thị công khai.
            </div>
        @endif

        @if($page->show_in_menu)
            <div class="alert alert-info">
                <i class="bi bi-list"></i>
                <strong>Trang hiển thị trong menu</strong> với thứ tự: {{ $page->menu_order }}
            </div>
        @endif
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <!-- Basic Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Thông tin cơ bản</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Tiêu đề:</strong></div>
                    <div class="col-sm-9">{{ $page->title }}</div>
                </div>
                
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Slug:</strong></div>
                    <div class="col-sm-9">
                        <code>{{ $page->slug }}</code>
                        <a href="{{ route('pages.show', $page->slug) }}" target="_blank" class="ms-2">
                            <i class="bi bi-box-arrow-up-right"></i>
                        </a>
                    </div>
                </div>

                @if($page->excerpt)
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Tóm tắt:</strong></div>
                    <div class="col-sm-9">{{ $page->excerpt }}</div>
                </div>
                @endif

                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Template:</strong></div>
                    <div class="col-sm-9">
                        <span class="badge bg-info">{{ ucfirst($page->template) }}</span>
                    </div>
                </div>

                @if($page->featured_image)
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Ảnh đại diện:</strong></div>
                    <div class="col-sm-9">
                        <img src="{{ $page->featured_image }}" alt="Featured Image" 
                             class="img-thumbnail" style="max-width: 200px;">
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Content -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Nội dung</h5>
            </div>
            <div class="card-body">
                <div class="content-preview">
                    {!! $page->content !!}
                </div>
            </div>
        </div>

        <!-- SEO Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Thông tin SEO</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Meta Title:</strong></div>
                    <div class="col-sm-9">
                        {{ $page->meta_title ?: $page->title }}
                        <small class="text-muted">({{ strlen($page->meta_title ?: $page->title) }} ký tự)</small>
                    </div>
                </div>

                @if($page->meta_description)
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Meta Description:</strong></div>
                    <div class="col-sm-9">
                        {{ $page->meta_description }}
                        <small class="text-muted">({{ strlen($page->meta_description) }} ký tự)</small>
                    </div>
                </div>
                @endif

                @if($page->meta_keywords)
                <div class="row mb-3">
                    <div class="col-sm-3"><strong>Meta Keywords:</strong></div>
                    <div class="col-sm-9">
                        @foreach(explode(',', $page->meta_keywords) as $keyword)
                            <span class="badge bg-secondary me-1">{{ trim($keyword) }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- SEO Preview -->
                <div class="mt-4">
                    <h6>Xem trước kết quả tìm kiếm:</h6>
                    <div class="seo-preview p-3 border rounded bg-light">
                        <div class="seo-title text-primary">
                            {{ $page->meta_title ?: $page->title }}
                        </div>
                        <div class="seo-url text-success small">
                            {{ route('pages.show', $page->slug) }}
                        </div>
                        <div class="seo-description small">
                            {{ $page->meta_description ?: Str::limit(strip_tags($page->content), 160) }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <!-- Status Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Trạng thái</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Xuất bản:</strong>
                    @if($page->is_published)
                        <span class="badge bg-success">Đã xuất bản</span>
                    @else
                        <span class="badge bg-warning">Bản nháp</span>
                    @endif
                </div>

                <div class="mb-3">
                    <strong>Hiển thị menu:</strong>
                    @if($page->show_in_menu)
                        <span class="badge bg-info">Có</span>
                        <small class="text-muted">(Thứ tự: {{ $page->menu_order }})</small>
                    @else
                        <span class="badge bg-secondary">Không</span>
                    @endif
                </div>

                <div class="mb-3">
                    <strong>Template:</strong>
                    <span class="badge bg-primary">{{ ucfirst($page->template) }}</span>
                </div>
            </div>
        </div>

        <!-- Publishing Information -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Thông tin xuất bản</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <strong>Người tạo:</strong><br>
                    {{ $page->creator->name ?? 'N/A' }}<br>
                    <small class="text-muted">{{ $page->created_at->format('d/m/Y H:i:s') }}</small>
                </div>

                <div class="mb-3">
                    <strong>Cập nhật lần cuối:</strong><br>
                    {{ $page->updater->name ?? 'N/A' }}<br>
                    <small class="text-muted">{{ $page->updated_at->format('d/m/Y H:i:s') }}</small>
                </div>

                <div class="mb-3">
                    <strong>Thời gian tồn tại:</strong><br>
                    <small class="text-muted">{{ $page->created_at->diffForHumans() }}</small>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Thao tác nhanh</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.pages.edit', $page) }}" class="btn btn-primary">
                        <i class="bi bi-pencil"></i> Chỉnh sửa
                    </a>
                    
                    <a href="{{ route('pages.show', $page->slug) }}" target="_blank" class="btn btn-outline-info">
                        <i class="bi bi-eye"></i> Xem trang live
                    </a>

                    @if($page->is_published)
                        <form action="{{ route('admin.pages.toggle-status', $page) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-outline-warning w-100">
                                <i class="bi bi-eye-slash"></i> Ẩn trang
                            </button>
                        </form>
                    @else
                        <form action="{{ route('admin.pages.toggle-status', $page) }}" method="POST" class="d-inline">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-outline-success w-100">
                                <i class="bi bi-eye"></i> Xuất bản
                            </button>
                        </form>
                    @endif

                    <button type="button" class="btn btn-outline-secondary" onclick="copyPageUrl()">
                        <i class="bi bi-copy"></i> Copy URL
                    </button>
                    
                    <hr>
                    
                    <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" 
                          class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa trang này?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-outline-danger w-100">
                            <i class="bi bi-trash"></i> Xóa trang
                        </button>
                    </form>
                </div>
            </div>
        </div>

        <!-- Statistics (if needed) -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Thống kê</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-end">
                            <h6 class="text-muted">Ký tự nội dung</h6>
                            <h4>{{ number_format(strlen(strip_tags($page->content))) }}</h4>
                        </div>
                    </div>
                    <div class="col-6">
                        <h6 class="text-muted">Từ khóa SEO</h6>
                        <h4>{{ $page->meta_keywords ? count(explode(',', $page->meta_keywords)) : 0 }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.content-preview {
    border: 1px solid #dee2e6;
    border-radius: 0.375rem;
    padding: 1rem;
    background-color: #f8f9fa;
    max-height: 500px;
    overflow-y: auto;
}

.seo-preview {
    max-width: 600px;
}

.seo-title {
    font-size: 18px;
    font-weight: normal;
    color: #1a0dab;
    text-decoration: none;
    cursor: pointer;
}

.seo-title:hover {
    text-decoration: underline;
}

.seo-url {
    font-size: 14px;
    color: #006621;
}

.seo-description {
    font-size: 13px;
    color: #545454;
    line-height: 1.58;
}
</style>
@endpush

@push('scripts')
<script>
function copyPageUrl() {
    const url = '{{ route("pages.show", $page->slug) }}';
    
    if (navigator.clipboard) {
        navigator.clipboard.writeText(url).then(function() {
            showToast('URL đã được copy!', 'success');
        });
    } else {
        // Fallback for older browsers
        const textArea = document.createElement('textarea');
        textArea.value = url;
        document.body.appendChild(textArea);
        textArea.select();
        document.execCommand('copy');
        document.body.removeChild(textArea);
        showToast('URL đã được copy!', 'success');
    }
}

function showToast(message, type = 'info') {
    // Create toast if not exists
    let toastContainer = document.getElementById('toast-container');
    if (!toastContainer) {
        toastContainer = document.createElement('div');
        toastContainer.id = 'toast-container';
        toastContainer.className = 'position-fixed top-0 end-0 p-3';
        toastContainer.style.zIndex = 1050;
        document.body.appendChild(toastContainer);
    }
    
    const toast = document.createElement('div');
    toast.className = `toast align-items-center text-white bg-${type} border-0`;
    toast.setAttribute('role', 'alert');
    toast.innerHTML = `
        <div class="d-flex">
            <div class="toast-body">${message}</div>
            <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast"></button>
        </div>
    `;
    
    toastContainer.appendChild(toast);
    
    const bsToast = new bootstrap.Toast(toast);
    bsToast.show();
    
    // Remove toast after it's hidden
    toast.addEventListener('hidden.bs.toast', function() {
        toast.remove();
    });
}
</script>
@endpush