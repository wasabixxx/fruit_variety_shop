@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6 col-lg-5">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-success text-white text-center">
                    <h4 class="mb-0">
                        <i class="bi bi-box-arrow-in-right"></i> Đăng nhập
                    </h4>
                </div>
                <div class="card-body p-4">
                    <!-- Session Messages -->
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle"></i>
                            <strong>Có lỗi xảy ra:</strong>
                            <ul class="mb-0 mt-2">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}">
                        @csrf
                        <div class="mb-3">
                            <label for="email" class="form-label fw-bold">
                                <i class="bi bi-envelope"></i> Email
                            </label>
                            <input type="email" class="form-control form-control-lg" id="email" name="email" 
                                   value="{{ old('email') }}" required placeholder="Nhập email của bạn">
                        </div>
                        
                        <div class="mb-4">
                            <label for="password" class="form-label fw-bold">
                                <i class="bi bi-lock"></i> Mật khẩu
                            </label>
                            <input type="password" class="form-control form-control-lg" id="password" name="password" 
                                   required placeholder="Nhập mật khẩu">
                        </div>
                        
                        <div class="d-grid mb-3">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="bi bi-box-arrow-in-right"></i> Đăng nhập
                            </button>
                        </div>
                    </form>
                    
                    <!-- Admin Account Info -->
                    <div class="alert alert-info">
                        <h6 class="alert-heading">
                            <i class="bi bi-info-circle"></i> Tài khoản Admin mặc định
                        </h6>
                        <hr>
                        <p class="mb-1"><strong>Email:</strong> admin@admin.com</p>
                        <p class="mb-0"><strong>Mật khẩu:</strong> admin</p>
                    </div>
                    
                    <div class="text-center">
                        <hr class="my-4">
                        <p class="text-muted mb-2">Chưa có tài khoản?</p>
                        <a href="{{ route('register') }}" class="btn btn-outline-success">
                            <i class="bi bi-person-plus"></i> Đăng ký ngay
                        </a>
                    </div>
                    
                    <div class="text-center mt-3">
                        <a href="/" class="text-decoration-none text-muted">
                            <i class="bi bi-arrow-left"></i> Quay về trang chủ
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 15px;
}

.form-control:focus {
    border-color: #28a745;
    box-shadow: 0 0 0 0.2rem rgba(40, 167, 69, 0.25);
}

.btn-success {
    background: linear-gradient(135deg, #28a745, #20c997);
    border: none;
}

.btn-success:hover {
    background: linear-gradient(135deg, #218838, #1ea085);
    transform: translateY(-1px);
}
</style>
@endsection
