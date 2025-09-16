<x-mail::message>
# 🎉 Khuyến mãi đặc biệt!

{{ $content }}

## 🎫 Mã voucher của bạn

<x-mail::panel>
**Mã voucher:** {{ $voucher->code }}

**Loại giảm giá:** 
@if($voucher->type === 'percentage')
Giảm {{ $voucher->amount }}%
@else
Giảm {{ number_format($voucher->amount, 0, ',', '.') }}đ
@endif

@if($voucher->minimum_order_amount)
**Đơn hàng tối thiểu:** {{ number_format($voucher->minimum_order_amount, 0, ',', '.') }}đ
@endif

**Có hiệu lực từ:** {{ $voucher->starts_at->format('d/m/Y H:i') }}  
**Hết hạn:** {{ $voucher->expires_at->format('d/m/Y H:i') }}  
**Số lượng:** {{ $voucher->usage_limit - $voucher->used_count }} lượt còn lại
</x-mail::panel>

<x-mail::button :url="route('home')">
Mua sắm ngay
</x-mail::button>

## 📝 Cách sử dụng:
1. Thêm sản phẩm yêu thích vào giỏ hàng
2. Trong quá trình thanh toán, nhập mã voucher: **{{ $voucher->code }}**
3. Áp dụng và tận hưởng ưu đãi!

⏰ **Lưu ý:** Voucher có số lượng và thời gian có hạn. Hãy nhanh tay để không bỏ lỡ cơ hội!

Cảm ơn bạn đã tin tưởng và đồng hành cùng {{ config('app.name') }}!

Trân trọng,<br>
{{ config('app.name') }}
</x-mail::message>
