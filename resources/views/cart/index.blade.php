@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4"><i class="bi bi-cart4"></i> Giỏ hàng của bạn</h1>
    @if(count($cart) > 0)
        <form method="POST" action="{{ route('cart.clear') }}" class="mb-3">
            @csrf
            <button type="submit" class="btn btn-outline-danger">
                <i class="bi bi-trash"></i> Xóa toàn bộ giỏ hàng
            </button>
        </form>
        <div class="table-responsive">
            <table class="table align-middle table-bordered bg-white shadow-sm">
                <thead class="table-success">
                    <tr>
                        <th></th>
                        <th>Sản phẩm</th>
                        <th>Đơn giá</th>
                        <th>Số lượng</th>
                        <th>Thành tiền</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($cart as $item)
                    <tr>
                        <td style="width: 100px;">
                            @if($item['image'])
                                <img src="{{ $item['image'] }}" alt="{{ $item['name'] }}" class="img-fluid rounded" style="height: 60px; object-fit: cover;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 60px; width: 100px;">
                                    <i class="bi bi-image text-muted" style="font-size: 2rem;"></i>
                                </div>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $item['name'] }}</strong>
                        </td>
                        <td class="text-success fw-bold">{{ number_format($item['price']) }} VNĐ</td>
                        <td style="width: 120px;">
                            <form method="POST" action="{{ route('cart.update', $item['id']) }}" class="d-flex align-items-center gap-2">
                                @csrf
                                <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" max="{{ $item['stock'] }}" class="form-control form-control-sm" style="width: 60px;">
                                <button type="submit" class="btn btn-sm btn-outline-success"><i class="bi bi-arrow-repeat"></i></button>
                            </form>
                        </td>
                        <td class="fw-bold">{{ number_format($item['price'] * $item['quantity']) }} VNĐ</td>
                        <td>
                            <form method="POST" action="{{ route('cart.remove', $item['id']) }}">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-danger"><i class="bi bi-x"></i></button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="text-end mt-4">
            <h4>Tổng cộng: <span class="text-success">{{ number_format($total) }} VNĐ</span></h4>
        </div>
        <div class="text-end mt-3">
            <a href="{{ route('products.index') }}" class="btn btn-outline-success me-2">
                <i class="bi bi-arrow-left"></i> Tiếp tục mua hàng
            </a>
            <a href="{{ route('orders.create') }}" class="btn btn-success">
                <i class="bi bi-credit-card"></i> Đặt hàng
            </a>
        </div>
    @else
        <div class="text-center py-5">
            <i class="bi bi-cart-x display-1 text-muted"></i>
            <h3 class="text-muted mt-3">Giỏ hàng của bạn đang trống</h3>
            <p class="text-muted">Hãy chọn sản phẩm để thêm vào giỏ hàng.</p>
            <a href="{{ route('products.index') }}" class="btn btn-success">
                <i class="bi bi-bag"></i> Xem sản phẩm
            </a>
        </div>
    @endif
</div>
@endsection
