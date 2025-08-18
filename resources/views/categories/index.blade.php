@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Hero Section -->
    <div class="row mb-5">
        <div class="col-12">
            <div class="bg-success text-white p-5 rounded-3 text-center">
                <h1 class="display-4 mb-3">
                    <i class="bi bi-tags"></i> Danh mục sản phẩm
                </h1>
                <p class="lead">Khám phá các loại hạt giống cây ăn quả chất lượng cao</p>
            </div>
        </div>
    </div>

    <!-- Categories Grid -->
    <div class="row">
        @forelse($categories as $category)
        <div class="col-md-6 col-lg-4 mb-4">
            <div class="card h-100 shadow-sm category-card">
                <div class="card-body d-flex flex-column">
                    <div class="text-center mb-3">
                        <div class="category-icon">
                            <i class="bi bi-flower2 text-success" style="font-size: 3rem;"></i>
                        </div>
                    </div>
                    
                    <h5 class="card-title text-center">{{ $category->name }}</h5>
                    
                    @if($category->description)
                        <p class="card-text text-muted flex-grow-1">
                            {{ Str::limit($category->description, 100) }}
                        </p>
                    @endif
                    
                    <div class="text-center mb-3">
                        <span class="badge bg-success">
                            {{ $category->products_count }} sản phẩm
                        </span>
                    </div>
                    
                    <div class="mt-auto">
                        <a href="{{ route('categories.show', $category) }}" 
                           class="btn btn-success w-100">
                            <i class="bi bi-eye"></i> Xem sản phẩm
                        </a>
                    </div>
                </div>
            </div>
        </div>
        @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="bi bi-tags display-1 text-muted"></i>
                <h3 class="text-muted mt-3">Chưa có danh mục nào</h3>
                <p class="text-muted">Hãy quay lại sau để xem các danh mục mới!</p>
                <a href="/" class="btn btn-success">Về trang chủ</a>
            </div>
        </div>
        @endforelse
    </div>

    <!-- Pagination -->
    @if($categories->hasPages())
        <div class="d-flex justify-content-center mt-5">
            {{ $categories->links() }}
        </div>
    @endif
</div>

<style>
.category-card {
    transition: transform 0.3s ease;
    border: none;
}

.category-card:hover {
    transform: translateY(-5px);
    box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
}

.category-icon {
    background: rgba(40, 167, 69, 0.1);
    border-radius: 50%;
    width: 80px;
    height: 80px;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto;
}
</style>
@endsection