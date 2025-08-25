@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="text-center">
                <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                <h1 class="mt-3 text-success">Đặt hàng thành công!</h1>
                <p class="lead text-muted">Cảm ơn bạn đã mua hàng tại Fruit Variety Shop.</p>
                
                <div class="card shadow-sm mt-4">
                    <div class="card-body">
                        <h5 class="card-title"><i class="bi bi-info-circle"></i> Thông tin quan trọng</h5>
                        <ul class="list-unstyled text-start">
                            <li><i class="bi bi-telephone"></i> Chúng tôi sẽ liên hệ với bạn trong vòng 24h để xác nhận đơn hàng.</li>
                            <li><i class="bi bi-truck"></i> Thời gian giao hàng: 1-3 ngày làm việc.</li>
                            <li><i class="bi bi-shield-check"></i> Bạn có thể kiểm tra hàng trước khi thanh toán (với COD).</li>
                        </ul>
                    </div>
                </div>
                
                <div class="mt-4">
                    <a href="{{ route('products.index') }}" class="btn btn-success me-2">
                        <i class="bi bi-bag"></i> Tiếp tục mua hàng
                    </a>
                    <a href="/" class="btn btn-outline-secondary">
                        <i class="bi bi-house"></i> Về trang chủ
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
