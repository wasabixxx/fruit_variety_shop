<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CartController extends Controller
{
    // Hiển thị giỏ hàng
    public function index(Request $request)
    {
        $cart = session('cart', []);
        $total = collect($cart)->sum(function($item) {
            return $item['price'] * $item['quantity'];
        });
        
        // Get applied voucher info
        $appliedVoucher = session('applied_voucher');
        $discountAmount = session('voucher_discount_amount', 0);
        
        return view('cart.index', compact('cart', 'total', 'appliedVoucher', 'discountAmount'));
    }

    // Thêm sản phẩm vào giỏ
    public function add(Request $request, $productId)
    {
        $product = \App\Models\Product::findOrFail($productId);
        $cart = session('cart', []);
        $qty = (int) $request->input('quantity', 1);
        if (isset($cart[$productId])) {
            $cart[$productId]['quantity'] += $qty;
        } else {
            $cart[$productId] = [
                'id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'image' => $product->image,
                'quantity' => $qty,
                'stock' => $product->stock,
            ];
        }
        session(['cart' => $cart]);
        return back()->with('success', 'Đã thêm sản phẩm vào giỏ hàng!');
    }

    // Cập nhật số lượng sản phẩm
    public function update(Request $request, $productId)
    {
        $cart = session('cart', []);
        if (isset($cart[$productId])) {
            $qty = max(1, (int) $request->input('quantity', 1));
            $cart[$productId]['quantity'] = $qty;
            session(['cart' => $cart]);
        }
        return back()->with('success', 'Đã cập nhật số lượng sản phẩm!');
    }

    // Xóa sản phẩm khỏi giỏ
    public function remove(Request $request, $productId)
    {
        $cart = session('cart', []);
        unset($cart[$productId]);
        session(['cart' => $cart]);
        return back()->with('success', 'Đã xóa sản phẩm khỏi giỏ hàng!');
    }

    // Xóa toàn bộ giỏ hàng
    public function clear()
    {
        session()->forget(['cart', 'applied_voucher', 'voucher_discount_amount']);
        return back()->with('success', 'Đã xóa toàn bộ giỏ hàng!');
    }
}
