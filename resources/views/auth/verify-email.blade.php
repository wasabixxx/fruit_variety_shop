@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg border-0">
                <div class="card-header bg-warning text-dark text-center">
                    <h4 class="mb-0">
                        <i class="bi bi-envelope-exclamation"></i> Xác nhận địa chỉ email
                    </h4>
                </div>
                <div class="card-body p-4 text-center">
                    <div class="mb-4">
                        <i class="bi bi-envelope-check display-1 text-warning"></i>
                    </div>
                    
                    <h5 class="mb-3">Vui lòng xác nhận địa chỉ email của bạn</h5>
                    
                    <p class="text-muted mb-4">
                        Chúng tôi đã gửi một email xác nhận đến địa chỉ: 
                        <strong>{{ auth()->user()->email }}</strong>
                    </p>
                    
                    <p class="text-muted mb-4">
                        Vui lòng kiểm tra hộp thư đến (và cả thư mục spam) để tìm email xác nhận, 
                        sau đó nhấp vào liên kết trong email để kích hoạt tài khoản.
                    </p>
                    
                    <!-- Resend verification form -->
                    <div class="border-top pt-4">
                        <p class="text-muted mb-3">Không nhận được email?</p>
                        
                        <form method="POST" action="{{ route('email.resend') }}" class="mb-3">
                            @csrf
                            <input type="hidden" name="email" value="{{ auth()->user()->email }}">
                            <button type="submit" class="btn btn-warning">
                                <i class="bi bi-arrow-clockwise"></i> Gửi lại email xác nhận
                            </button>
                        </form>
                        
                        <div class="mt-3">
                            <small class="text-muted">
                                Hoặc bạn có thể 
                                <a href="{{ route('logout') }}" 
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    đăng xuất
                                </a> 
                                và đăng ký lại với email khác
                            </small>
                            
                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                @csrf
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Tips card -->
            <div class="card mt-4 border-info">
                <div class="card-body">
                    <h6 class="card-title text-info">
                        <i class="bi bi-lightbulb"></i> Mẹo hữu ích
                    </h6>
                    <ul class="mb-0 text-muted small">
                        <li>Kiểm tra thư mục spam/junk mail</li>
                        <li>Thêm địa chỉ email của chúng tôi vào danh sách an toàn</li>
                        <li>Email xác nhận có thể mất vài phút để được gửi đến</li>
                        <li>Liên kết xác nhận sẽ hết hạn sau 24 giờ</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.card {
    border-radius: 15px;
}

.btn-warning {
    background: linear-gradient(135deg, #ffc107, #fd7e14);
    border: none;
    color: white;
}

.btn-warning:hover {
    background: linear-gradient(135deg, #e0a800, #dc6502);
    transform: translateY(-1px);
    color: white;
}
</style>
@endsection
