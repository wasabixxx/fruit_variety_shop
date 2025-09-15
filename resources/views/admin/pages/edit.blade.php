@extends('admin.layout')

@section('title', 'Chỉnh sửa trang: ' . $page->title)

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-2">Chỉnh sửa trang</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.pages.index') }}">Quản lý trang</a></li>
                <li class="breadcrumb-item active">{{ $page->title }}</li>
            </ol>
        </nav>
    </div>
    <div class="btn-group">
        <a href="{{ route('pages.show', $page->slug) }}" target="_blank" class="btn btn-outline-info">
            <i class="bi bi-eye"></i> Xem trang
        </a>
        <a href="{{ route('admin.pages.index') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>
</div>

<!-- Status Alert -->
@if(!$page->is_published)
<div class="alert alert-warning mb-4">
    <i class="bi bi-exclamation-triangle"></i>
    <strong>Trang chưa được xuất bản.</strong> Trang này không hiển thị công khai.
</div>
@endif

<form action="{{ route('admin.pages.update', $page) }}" method="POST" id="pageForm">
    @csrf
    @method('PUT')
    
    <div class="row">
        <div class="col-md-8">
            <!-- Basic Information -->
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Thông tin cơ bản</h5>
                    <small class="text-muted">
                        Tạo: {{ $page->created_at->format('d/m/Y H:i') }} | 
                        Cập nhật: {{ $page->updated_at->format('d/m/Y H:i') }}
                    </small>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title', $page->title) }}" required 
                               placeholder="Nhập tiêu đề trang">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="slug" class="form-label">Slug (URL)</label>
                        <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                               id="slug" name="slug" value="{{ old('slug', $page->slug) }}" 
                               placeholder="Để trống để tự động tạo từ tiêu đề">
                        <div class="form-text">
                            <i class="bi bi-info-circle"></i> URL của trang: 
                            <a href="{{ route('pages.show', $page->slug) }}" target="_blank" id="slugPreview">
                                {{ config('app.url') }}/pages/{{ $page->slug }}
                            </a>
                        </div>
                        @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="excerpt" class="form-label">Tóm tắt</label>
                        <textarea class="form-control @error('excerpt') is-invalid @enderror" 
                                  id="excerpt" name="excerpt" rows="3" 
                                  placeholder="Mô tả ngắn về nội dung trang (tùy chọn)">{{ old('excerpt', $page->excerpt) }}</textarea>
                        @error('excerpt')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label">Nội dung <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('content') is-invalid @enderror" 
                                  id="content" name="content" rows="15" required 
                                  placeholder="Nhập nội dung trang...">{{ old('content', $page->content) }}</textarea>
                        @error('content')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- SEO Settings -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Cài đặt SEO</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="meta_title" class="form-label">Meta Title</label>
                        <input type="text" class="form-control @error('meta_title') is-invalid @enderror" 
                               id="meta_title" name="meta_title" value="{{ old('meta_title', $page->meta_title) }}" 
                               placeholder="Để trống để sử dụng tiêu đề chính">
                        <div class="form-text">
                            Khuyến nghị: 50-60 ký tự | Hiện tại: <span id="metaTitleCount">{{ strlen($page->meta_title ?: $page->title) }}</span> ký tự
                        </div>
                        @error('meta_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="meta_description" class="form-label">Meta Description</label>
                        <textarea class="form-control @error('meta_description') is-invalid @enderror" 
                                  id="meta_description" name="meta_description" rows="3" 
                                  placeholder="Mô tả cho công cụ tìm kiếm">{{ old('meta_description', $page->meta_description) }}</textarea>
                        <div class="form-text">
                            Khuyến nghị: 150-160 ký tự | Hiện tại: <span id="metaDescCount">{{ strlen($page->meta_description) }}</span> ký tự
                        </div>
                        @error('meta_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="meta_keywords" class="form-label">Meta Keywords</label>
                        <input type="text" class="form-control @error('meta_keywords') is-invalid @enderror" 
                               id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords', $page->meta_keywords) }}" 
                               placeholder="từ khóa 1, từ khóa 2, từ khóa 3">
                        <div class="form-text">Các từ khóa phân cách bởi dấu phẩy</div>
                        @error('meta_keywords')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Page History -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Lịch sử</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Người tạo:</strong> {{ $page->creator->name ?? 'N/A' }}</p>
                            <p><strong>Ngày tạo:</strong> {{ $page->created_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Người cập nhật:</strong> {{ $page->updater->name ?? 'N/A' }}</p>
                            <p><strong>Cập nhật lần cuối:</strong> {{ $page->updated_at->format('d/m/Y H:i:s') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Publishing Options -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Xuất bản</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="is_published" 
                                   name="is_published" value="1" 
                                   {{ old('is_published', $page->is_published) ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_published">
                                Xuất bản
                            </label>
                        </div>
                        <div class="form-text">
                            Trang {{ $page->is_published ? 'đang' : 'chưa' }} được xuất bản
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="show_in_menu" 
                                   name="show_in_menu" value="1" 
                                   {{ old('show_in_menu', $page->show_in_menu) ? 'checked' : '' }}>
                            <label class="form-check-label" for="show_in_menu">
                                Hiển thị trong menu
                            </label>
                        </div>
                        <div class="form-text">
                            Trang {{ $page->show_in_menu ? 'đang' : 'không' }} hiển thị trong menu
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="menu_order" class="form-label">Thứ tự menu</label>
                        <input type="number" class="form-control @error('menu_order') is-invalid @enderror" 
                               id="menu_order" name="menu_order" value="{{ old('menu_order', $page->menu_order) }}" 
                               min="0" placeholder="0">
                        <div class="form-text">Số thứ tự hiển thị trong menu (0 = đầu tiên)</div>
                        @error('menu_order')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Template Settings -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Template</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="template" class="form-label">Chọn template <span class="text-danger">*</span></label>
                        <select class="form-select @error('template') is-invalid @enderror" 
                                id="template" name="template" required>
                            @foreach($templates as $key => $name)
                                <option value="{{ $key }}" 
                                        {{ old('template', $page->template) == $key ? 'selected' : '' }}>
                                    {{ $name }}
                                </option>
                            @endforeach
                        </select>
                        @error('template')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="featured_image" class="form-label">Ảnh đại diện</label>
                        <input type="url" class="form-control @error('featured_image') is-invalid @enderror" 
                               id="featured_image" name="featured_image" value="{{ old('featured_image', $page->featured_image) }}" 
                               placeholder="https://example.com/image.jpg">
                        <div class="form-text">URL của ảnh đại diện (tùy chọn)</div>
                        @error('featured_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                        @if($page->featured_image)
                            <div class="mt-2">
                                <img src="{{ $page->featured_image }}" alt="Featured Image" 
                                     class="img-thumbnail" style="max-width: 100px;">
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Cập nhật trang
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="previewPage()">
                            <i class="bi bi-eye"></i> Xem trước
                        </button>
                        <a href="{{ route('pages.show', $page->slug) }}" target="_blank" class="btn btn-outline-info">
                            <i class="bi bi-box-arrow-up-right"></i> Xem trang live
                        </a>
                        <a href="{{ route('admin.pages.index') }}" class="btn btn-outline-danger">
                            <i class="bi bi-x-lg"></i> Hủy
                        </a>
                    </div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="card mt-3">
                <div class="card-header">
                    <h5 class="mb-0">Thao tác nhanh</h5>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        @if($page->is_published)
                            <form action="{{ route('admin.pages.toggle-status', $page) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-outline-warning btn-sm w-100">
                                    <i class="bi bi-eye-slash"></i> Ẩn trang
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.pages.toggle-status', $page) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-outline-success btn-sm w-100">
                                    <i class="bi bi-eye"></i> Xuất bản
                                </button>
                            </form>
                        @endif
                        
                        <form action="{{ route('admin.pages.destroy', $page) }}" method="POST" 
                              class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xóa trang này?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-outline-danger btn-sm w-100">
                                <i class="bi bi-trash"></i> Xóa trang
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('styles')
<!-- Include Quill CSS -->
<link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
<style>
.ql-editor {
    min-height: 300px;
}
</style>
@endpush

@push('scripts')
<!-- Include Quill JS -->
<script src="https://cdn.quilljs.com/1.3.6/quill.min.js"></script>

<script>
// Initialize Quill editor
var quill = new Quill('#content', {
    theme: 'snow',
    modules: {
        toolbar: [
            [{ 'header': [1, 2, 3, 4, 5, 6, false] }],
            ['bold', 'italic', 'underline', 'strike'],
            [{ 'color': [] }, { 'background': [] }],
            [{ 'list': 'ordered'}, { 'list': 'bullet' }],
            [{ 'align': [] }],
            ['link', 'image'],
            ['clean']
        ]
    }
});

// Auto-generate slug from title
document.getElementById('title').addEventListener('input', function() {
    const title = this.value;
    const slug = title.toLowerCase()
                      .replace(/[^\w\s-]/g, '')
                      .replace(/\s+/g, '-')
                      .replace(/-+/g, '-')
                      .trim('-');
    
    document.getElementById('slug').value = slug;
    updateSlugPreview(slug);
});

document.getElementById('slug').addEventListener('input', function() {
    updateSlugPreview(this.value);
});

function updateSlugPreview(slug) {
    const link = document.getElementById('slugPreview');
    link.href = '{{ config("app.url") }}/pages/' + (slug || '{{ $page->slug }}');
    link.textContent = '{{ config("app.url") }}/pages/' + (slug || '{{ $page->slug }}');
}

// Character counting for SEO fields
document.getElementById('meta_title').addEventListener('input', function() {
    document.getElementById('metaTitleCount').textContent = this.value.length;
});

document.getElementById('meta_description').addEventListener('input', function() {
    document.getElementById('metaDescCount').textContent = this.value.length;
});

// Handle form submission
document.getElementById('pageForm').addEventListener('submit', function() {
    // Update textarea with Quill content
    document.querySelector('textarea[name="content"]').value = quill.root.innerHTML;
});

function previewPage() {
    // Update textarea with Quill content
    document.querySelector('textarea[name="content"]').value = quill.root.innerHTML;
    
    // Create preview form
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("pages.show", $page->slug) }}';
    form.target = '_blank';
    
    // Copy form data
    const formData = new FormData(document.getElementById('pageForm'));
    for (let [key, value] of formData.entries()) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = key;
        input.value = value;
        form.appendChild(input);
    }
    
    document.body.appendChild(form);
    form.submit();
    document.body.removeChild(form);
}
</script>
@endpush