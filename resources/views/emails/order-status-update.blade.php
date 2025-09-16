<x-mail::message>
# Cập nhật đơn hàng #{{ $order->id }}

Chào {{ $order->customer_name }},

Đơn hàng #{{ $order->id }} của bạn đã được cập nhật trạng thái.

## Thông tin cập nhật

**Mã đơn hàng:** #{{ $order->id }}  
**Trạng thái mới:** {{ $statusText }}  
**Thời gian cập nhật:** {{ now()->format('d/m/Y H:i') }}

@switch($newStatus)
    @case('confirmed')
        Đơn hàng của bạn đã được xác nhận. Chúng tôi sẽ tiến hành chuẩn bị hàng và giao đến bạn trong thời gian sớm nhất.
        @break
    @case('processing')
        Đơn hàng của bạn đang được xử lý và chuẩn bị. Vui lòng chờ thông báo tiếp theo.
        @break
    @case('shipping')
        Đơn hàng của bạn đang được giao đến địa chỉ: {{ $order->customer_address }}. 
        Vui lòng chú ý điện thoại để nhận hàng.
        @break
    @case('delivered')
        🎉 Đơn hàng của bạn đã được giao thành công! 
        Cảm ơn bạn đã tin tướng và mua sắm tại {{ config('app.name') }}.
        @break
    @case('cancelled')
        Đơn hàng của bạn đã bị hủy. Nếu có thắc mắc, vui lòng liên hệ với chúng tôi.
        @break
    @case('refunded')
        Đơn hàng của bạn đã được hoàn tiền. Số tiền sẽ được chuyển về tài khoản của bạn trong 3-5 ngày làm việc.
        @break
    @default
        Trạng thái đơn hàng của bạn đã được cập nhật.
@endswitch

<x-mail::button :url="route('orders.show', $order->id)">
Xem chi tiết đơn hàng
</x-mail::button>

@if($newStatus === 'delivered')
Đánh giá sản phẩm để nhận điểm thưởng và giúp khách hàng khác có trải nghiệm tốt hơn.

<x-mail::button :url="route('products.show', $order->orderItems->first()->product_id)">
Đánh giá sản phẩm
</x-mail::button>
@endif

Nếu có bất kỳ thắc mắc nào, vui lòng liên hệ với chúng tôi.

Trân trọng,<br>
{{ config('app.name') }}
</x-mail::message>
