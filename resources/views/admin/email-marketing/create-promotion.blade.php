@extends('admin.layout')

@section('title', 'Tạo Email Khuyến Mãi')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-2">Tạo Email Khuyến Mãi</h1>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item"><a href="{{ route('admin.email-marketing.index') }}">Email Marketing</a></li>
                    <li class="breadcrumb-item active">Tạo Email Khuyến Mãi</li>
                </ol>
            </nav>
        </div>
        <a href="{{ route('admin.email-marketing.index') }}" class="btn btn-secondary">
            <i class="bi bi-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-primary">Thông tin email</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('admin.email-marketing.send-promotion') }}" method="POST">
                        @csrf
                        
                        <div class="mb-3">
                            <label for="voucher_id" class="form-label">Chọn Voucher</label>
                            <select class="form-select @error('voucher_id') is-invalid @enderror" 
                                    id="voucher_id" name="voucher_id" required>
                                <option value="">-- Chọn voucher --</option>
                                @foreach($vouchers as $voucher)
                                    <option value="{{ $voucher->id }}" 
                                            data-code="{{ $voucher->code }}"
                                            data-type="{{ $voucher->type }}"
                                            data-value="{{ $voucher->amount }}"
                                            data-min="{{ $voucher->minimum_order_amount }}"
                                            data-valid-from="{{ $voucher->starts_at ? $voucher->starts_at->format('d/m/Y') : '' }}"
                                            data-valid-until="{{ $voucher->expires_at ? $voucher->expires_at->format('d/m/Y') : '' }}"
                                            {{ old('voucher_id') == $voucher->id ? 'selected' : '' }}>
                                        {{ $voucher->code }} - 
                                        @if($voucher->type === 'percentage')
                                            Giảm {{ $voucher->amount }}%
                                        @else
                                            Giảm {{ number_format($voucher->amount, 0, ',', '.') }}đ
                                        @endif
                                    </option>
                                @endforeach
                            </select>
                            @error('voucher_id')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="subject" class="form-label">Tiêu đề Email</label>
                            <input type="text" class="form-control @error('subject') is-invalid @enderror" 
                                   id="subject" name="subject" value="{{ old('subject') }}" 
                                   placeholder="Ví dụ: 🎉 Khuyến mãi đặc biệt - Giảm 50% tất cả sản phẩm" required>
                            @error('subject')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label for="content" class="form-label">Nội dung Email</label>
                            <textarea class="form-control @error('content') is-invalid @enderror" 
                                      id="content" name="content" rows="6" required 
                                      placeholder="Viết nội dung mô tả về chương trình khuyến mãi...">{{ old('content') }}</textarea>
                            @error('content')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                            <div class="form-text">
                                Nội dung này sẽ hiển thị trong email trước thông tin voucher.
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="send_to" class="form-label">Gửi đến</label>
                            <select class="form-select @error('send_to') is-invalid @enderror" 
                                    id="send_to" name="send_to" required>
                                <option value="all" {{ old('send_to') === 'all' ? 'selected' : '' }}>
                                    Tất cả (Newsletter subscribers + Users)
                                </option>
                                <option value="subscribers" {{ old('send_to') === 'subscribers' ? 'selected' : '' }}>
                                    Chỉ Newsletter Subscribers
                                </option>
                                <option value="users" {{ old('send_to') === 'users' ? 'selected' : '' }}>
                                    Chỉ Registered Users
                                </option>
                            </select>
                            @error('send_to')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-send"></i> Gửi Email Khuyến Mãi
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card shadow">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-info">Thông tin Voucher</h6>
                </div>
                <div class="card-body">
                    <div id="voucher-info" style="display: none;">
                        <div class="alert alert-info">
                            <h6 class="alert-heading">Chi tiết voucher:</h6>
                            <hr>
                            <p class="mb-1"><strong>Mã:</strong> <span id="info-code"></span></p>
                            <p class="mb-1"><strong>Loại giảm:</strong> <span id="info-discount"></span></p>
                            <p class="mb-1"><strong>Thời gian:</strong> <span id="info-period"></span></p>
                            <div id="info-conditions"></div>
                        </div>
                    </div>
                    <div id="no-voucher" class="text-muted">
                        Chọn voucher để xem thông tin chi tiết.
                    </div>
                </div>
            </div>

            <div class="card shadow mt-4">
                <div class="card-header">
                    <h6 class="m-0 font-weight-bold text-success">Mẫu nội dung gợi ý</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <button type="button" class="btn btn-outline-primary btn-sm w-100 mb-2" 
                                onclick="useTemplate('seasonal')">
                            🌟 Khuyến mãi theo mùa
                        </button>
                        <button type="button" class="btn btn-outline-success btn-sm w-100 mb-2" 
                                onclick="useTemplate('flash')">
                            ⚡ Flash Sale
                        </button>
                        <button type="button" class="btn btn-outline-warning btn-sm w-100" 
                                onclick="useTemplate('special')">
                            🎁 Ưu đãi đặc biệt
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.getElementById('voucher_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const voucherInfo = document.getElementById('voucher-info');
    const noVoucher = document.getElementById('no-voucher');
    
    if (selectedOption.value) {
        // Show voucher info
        document.getElementById('info-code').textContent = selectedOption.dataset.code;
        
        let discount = '';
        if (selectedOption.dataset.type === 'percentage') {
            discount = `Giảm ${selectedOption.dataset.value}%`;
        } else {
            discount = `Giảm ${new Intl.NumberFormat('vi-VN').format(selectedOption.dataset.value)}đ`;
        }
        document.getElementById('info-discount').textContent = discount;
        
        document.getElementById('info-period').textContent = 
            `${selectedOption.dataset.validFrom} - ${selectedOption.dataset.validUntil}`;
        
        let conditions = '';
        if (selectedOption.dataset.min) {
            conditions += `<p class="mb-1"><strong>Đơn tối thiểu:</strong> ${new Intl.NumberFormat('vi-VN').format(selectedOption.dataset.min)}đ</p>`;
        }
        if (selectedOption.dataset.max) {
            conditions += `<p class="mb-0"><strong>Giảm tối đa:</strong> ${new Intl.NumberFormat('vi-VN').format(selectedOption.dataset.max)}đ</p>`;
        }
        document.getElementById('info-conditions').innerHTML = conditions;
        
        voucherInfo.style.display = 'block';
        noVoucher.style.display = 'none';
    } else {
        voucherInfo.style.display = 'none';
        noVoucher.style.display = 'block';
    }
});

function useTemplate(type) {
    const templates = {
        seasonal: `🌟 Chào mừng mùa mới với những ưu đãi tuyệt vời!

Chúng tôi rất vui được mang đến cho bạn chương trình khuyến mãi đặc biệt với mức giảm giá hấp dẫn. Đây là cơ hội tuyệt vời để bạn sở hữu những sản phẩm chất lượng với giá ưu đãi nhất.

Hãy nhanh tay đặt hàng để không bỏ lỡ cơ hội này!`,
        
        flash: `⚡ FLASH SALE - Giảm giá sốc trong thời gian có hạn!

Chỉ trong thời gian ngắn, chúng tôi mang đến cho bạn mức giảm giá không thể bỏ lỡ. Số lượng có hạn, thời gian có hạn!

Đặt hàng ngay để đảm bảo bạn không bỏ lỡ cơ hội tuyệt vời này. Khi hết hàng, bạn sẽ phải chờ đợi rất lâu mới có cơ hội như vậy!`,
        
        special: `🎁 Ưu đãi đặc biệt dành riêng cho bạn!

Với tư cách là khách hàng thân thiết, chúng tôi dành tặng bạn mã giảm giá đặc biệt này. Đây là lời cảm ơn chân thành từ chúng tôi vì sự tin tưởng và ủng hộ của bạn.

Sử dụng ngay mã giảm giá để tận hưởng những sản phẩm yêu thích với mức giá ưu đãi!`
    };
    
    document.getElementById('content').value = templates[type];
}
</script>
@endsection