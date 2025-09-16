@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>{{ $success ? 'Hủy đăng ký thành công' : 'Hủy đăng ký thất bại' }}</h4>
                </div>

                <div class="card-body text-center">
                    @if($success)
                        <div class="alert alert-success">
                            <i class="bi bi-check-circle-fill fs-1 text-success mb-3 d-block"></i>
                            <h5>{{ $message }}</h5>
                            @if(isset($email))
                                <p class="text-muted">Email: {{ $email }}</p>
                            @endif
                        </div>

                        <p class="mb-4">
                            Bạn sẽ không nhận được email khuyến mãi và tin tức từ chúng tôi nữa.
                        </p>

                        <div class="d-flex justify-content-center gap-3">
                            <a href="{{ route('home') }}" class="btn btn-primary">
                                <i class="bi bi-house"></i> Về trang chủ
                            </a>
                            @if(isset($email))
                                <a href="{{ route('newsletter.resubscribe', $token ?? '') }}" class="btn btn-outline-success">
                                    <i class="bi bi-arrow-clockwise"></i> Đăng ký lại
                                </a>
                            @endif
                        </div>
                    @else
                        <div class="alert alert-danger">
                            <i class="bi bi-exclamation-circle-fill fs-1 text-danger mb-3 d-block"></i>
                            <h5>{{ $message }}</h5>
                        </div>

                        <p class="mb-4">
                            Vui lòng kiểm tra lại đường link hoặc liên hệ với chúng tôi để được hỗ trợ.
                        </p>

                        <a href="{{ route('home') }}" class="btn btn-primary">
                            <i class="bi bi-house"></i> Về trang chủ
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection