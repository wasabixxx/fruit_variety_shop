<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class OrderController extends Controller
{
    // Hiển thị trang chọn phương thức thanh toán và xác nhận đơn hàng
    public function create(Request $request)
    {
        $cart = session('cart', []);
        $total = collect($cart)->sum(function($item) {
            return $item['price'] * $item['quantity'];
        });
        if ($total <= 0 || count($cart) === 0) {
            return redirect()->route('cart.index')->with('warning', 'Giỏ hàng trống!');
        }
        return view('orders.create', compact('cart', 'total'));
    }

    // Xử lý đặt hàng với phương thức thanh toán được chọn
    public function store(Request $request)
    {
        $cart = session('cart', []);
        $total = collect($cart)->sum(function($item) {
            return $item['price'] * $item['quantity'];
        });
        if ($total <= 0 || count($cart) === 0) {
            return redirect()->route('cart.index')->with('warning', 'Giỏ hàng trống!');
        }

        $request->validate([
            'payment_method' => 'required|in:cod,qr',
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_address' => 'required|string|max:500'
        ]);

        $paymentMethod = $request->input('payment_method');
        
        // Ở đây có thể lưu đơn hàng vào DB nếu muốn
        // Lưu thông tin đơn hàng vào session để hiển thị
        session([
            'order_info' => [
                'items' => $cart,
                'total' => $total,
                'payment_method' => $paymentMethod,
                'customer_name' => $request->input('customer_name'),
                'customer_phone' => $request->input('customer_phone'),
                'customer_address' => $request->input('customer_address'),
                'order_time' => now()->format('d/m/Y H:i:s')
            ]
        ]);

        if ($paymentMethod === 'qr') {
            // Chuyển đến trang thanh toán QR
            return redirect()->route('orders.payment');
        } else {
            // Thanh toán khi nhận hàng - hoàn tất đơn hàng
            session()->forget('cart');
            return redirect()->route('orders.success')->with('success', 'Đặt hàng thành công! Chúng tôi sẽ liên hệ với bạn để giao hàng.');
        }
    }

    // Hiển thị trang thanh toán QR
    public function payment()
    {
        $orderInfo = session('order_info');
        if (!$orderInfo) {
            return redirect()->route('cart.index')->with('error', 'Không tìm thấy thông tin đơn hàng!');
        }
        return view('orders.payment', compact('orderInfo'));
    }

    // Xác nhận đã thanh toán QR
    public function confirmPayment(Request $request)
    {
        $orderInfo = session('order_info');
        if (!$orderInfo) {
            return redirect()->route('cart.index')->with('error', 'Không tìm thấy thông tin đơn hàng!');
        }
        
        // Xóa giỏ hàng và thông tin đơn hàng
        session()->forget(['cart', 'order_info']);
        return redirect()->route('orders.success')->with('success', 'Thanh toán thành công! Chúng tôi sẽ xử lý đơn hàng của bạn.');
    }

    // Trang thành công
    public function success()
    {
        return view('orders.success');
    }
}
