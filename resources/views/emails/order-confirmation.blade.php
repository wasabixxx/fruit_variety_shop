<x-mail::message>
# Xác nhận đơn hàng #{{ $order->id }}

Chào {{ $order->customer_name }},

Cảm ơn bạn đã đặt hàng tại {{ config('app.name') }}! Đơn hàng của bạn đã được tiếp nhận và đang được xử lý.

## Thông tin đơn hàng

**Mã đơn hàng:** #{{ $order->id }}  
**Ngày đặt:** {{ $order->created_at->format('d/m/Y H:i') }}  
**Tình trạng:** {{ ucfirst($order->order_status) }}  
**Thanh toán:** {{ ucfirst($order->payment_method) }}

## Thông tin nhận hàng

**Người nhận:** {{ $order->customer_name }}  
**Số điện thoại:** {{ $order->customer_phone }}  
**Địa chỉ:** {{ $order->customer_address }}

@if($order->note)
**Ghi chú:** {{ $order->note }}
@endif

## Chi tiết đơn hàng

<x-mail::table>
| Sản phẩm | Số lượng | Đơn giá | Thành tiền |
|:---------|:--------:|--------:|-----------:|
@foreach($orderItems as $item)
| {{ $item->product->name }} | {{ $item->quantity }} | {{ number_format($item->price, 0, ',', '.') }}đ | {{ number_format($item->quantity * $item->price, 0, ',', '.') }}đ |
@endforeach
</x-mail::table>

@if($order->voucher_code)
**Mã giảm giá:** {{ $order->voucher_code }}  
**Giảm giá:** -{{ number_format($order->discount_amount, 0, ',', '.') }}đ
@endif

**Tổng cộng:** {{ number_format($order->total_amount, 0, ',', '.') }}đ

<x-mail::button :url="route('orders.show', $order->id)">
Xem chi tiết đơn hàng
</x-mail::button>

Chúng tôi sẽ liên hệ với bạn trong thời gian sớm nhất để xác nhận và giao hàng.

Nếu có bất kỳ thắc mắc nào, vui lòng liên hệ với chúng tôi qua email hoặc hotline.

Trân trọng,<br>
{{ config('app.name') }}
</x-mail::message>
