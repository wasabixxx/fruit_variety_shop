@extends('admin.layout')

@section('title', 'Tạo trang mới')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-2">Tạo trang mới</h1>
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.pages.index') }}">Quản lý trang</a></li>
                <li class="breadcrumb-item active">Tạo mới</li>
            </ol>
        </nav>
    </div>
    <a href="{{ route('admin.pages.index') }}" class="btn btn-outline-secondary">
        <i class="bi bi-arrow-left"></i> Quay lại
    </a>
</div>

<form action="{{ route('admin.pages.store') }}" method="POST" id="pageForm">
    @csrf
    
    <div class="row">
        <div class="col-md-8">
            <!-- Basic Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Thông tin cơ bản</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="title" class="form-label">Tiêu đề <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('title') is-invalid @enderror" 
                               id="title" name="title" value="{{ old('title') }}" required 
                               placeholder="Nhập tiêu đề trang">
                        @error('title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="slug" class="form-label">Slug (URL)</label>
                        <input type="text" class="form-control @error('slug') is-invalid @enderror" 
                               id="slug" name="slug" value="{{ old('slug') }}" 
                               placeholder="Để trống để tự động tạo từ tiêu đề">
                        <div class="form-text">
                            <i class="bi bi-info-circle"></i> URL của trang sẽ là: <span id="slugPreview">your-domain.com/pages/</span>
                        </div>
                        @error('slug')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="excerpt" class="form-label">Tóm tắt</label>
                        <textarea class="form-control @error('excerpt') is-invalid @enderror" 
                                  id="excerpt" name="excerpt" rows="3" 
                                  placeholder="Mô tả ngắn về nội dung trang (tùy chọn)">{{ old('excerpt') }}</textarea>
                        @error('excerpt')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="content" class="form-label">Nội dung <span class="text-danger">*</span></label>
                        <textarea class="form-control @error('content') is-invalid @enderror" 
                                  id="content" name="content" rows="15" required 
                                  placeholder="Nhập nội dung trang...">{{ old('content') }}</textarea>
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
                               id="meta_title" name="meta_title" value="{{ old('meta_title') }}" 
                               placeholder="Để trống để sử dụng tiêu đề chính">
                        <div class="form-text">Khuyến nghị: 50-60 ký tự</div>
                        @error('meta_title')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="meta_description" class="form-label">Meta Description</label>
                        <textarea class="form-control @error('meta_description') is-invalid @enderror" 
                                  id="meta_description" name="meta_description" rows="3" 
                                  placeholder="Mô tả cho công cụ tìm kiếm">{{ old('meta_description') }}</textarea>
                        <div class="form-text">Khuyến nghị: 150-160 ký tự</div>
                        @error('meta_description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="meta_keywords" class="form-label">Meta Keywords</label>
                        <input type="text" class="form-control @error('meta_keywords') is-invalid @enderror" 
                               id="meta_keywords" name="meta_keywords" value="{{ old('meta_keywords') }}" 
                               placeholder="từ khóa 1, từ khóa 2, từ khóa 3">
                        <div class="form-text">Các từ khóa phân cách bởi dấu phẩy</div>
                        @error('meta_keywords')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
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
                                   name="is_published" value="1" {{ old('is_published') ? 'checked' : '' }}>
                            <label class="form-check-label" for="is_published">
                                Xuất bản ngay
                            </label>
                        </div>
                        <div class="form-text">Trang sẽ hiển thị công khai sau khi tạo</div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" id="show_in_menu" 
                                   name="show_in_menu" value="1" {{ old('show_in_menu') ? 'checked' : '' }}>
                            <label class="form-check-label" for="show_in_menu">
                                Hiển thị trong menu
                            </label>
                        </div>
                        <div class="form-text">Trang sẽ xuất hiện trong menu điều hướng</div>
                    </div>

                    <div class="mb-3">
                        <label for="menu_order" class="form-label">Thứ tự menu</label>
                        <input type="number" class="form-control @error('menu_order') is-invalid @enderror" 
                               id="menu_order" name="menu_order" value="{{ old('menu_order', 0) }}" 
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
                                <option value="{{ $key }}" {{ old('template', 'default') == $key ? 'selected' : '' }}>
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
                               id="featured_image" name="featured_image" value="{{ old('featured_image') }}" 
                               placeholder="https://example.com/image.jpg">
                        <div class="form-text">URL của ảnh đại diện (tùy chọn)</div>
                        @error('featured_image')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-lg"></i> Tạo trang
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="previewPage()">
                            <i class="bi bi-eye"></i> Xem trước
                        </button>
                        <a href="{{ route('admin.pages.index') }}" class="btn btn-outline-danger">
                            <i class="bi bi-x-lg"></i> Hủy
                        </a>
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
    
    if (!document.getElementById('slug').value) {
        document.getElementById('slug').value = slug;
        updateSlugPreview(slug);
    }
});

document.getElementById('slug').addEventListener('input', function() {
    updateSlugPreview(this.value);
});

function updateSlugPreview(slug) {
    document.getElementById('slugPreview').textContent = 
        window.location.origin + '/pages/' + (slug || 'your-slug');
}

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
    form.action = '{{ route("admin.pages.index") }}'; // Temporary preview route
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

// Initialize slug preview
updateSlugPreview('');
</script>
@endpush