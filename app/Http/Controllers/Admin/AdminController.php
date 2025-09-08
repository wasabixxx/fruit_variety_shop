<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    public function dashboard()
    {
        $totalProducts = Product::count();
        $totalCategories = Category::count();
        $totalOrders = Order::count();
        $totalUsers = User::count();
        $recentOrders = Order::with(['user', 'orderItems.product'])
                            ->orderBy('created_at', 'desc')
                            ->limit(5)
                            ->get();
        
        return view('admin.dashboard', compact(
            'totalProducts', 
            'totalCategories', 
            'totalOrders', 
            'totalUsers', 
            'recentOrders'
        ));
    }

    // Quản lý đơn hàng
    public function orders()
    {
        $orders = Order::with(['user', 'orderItems.product'])->orderBy('created_at', 'desc')->paginate(15);
        return view('admin.orders.index', compact('orders'));
    }

    public function orderDetail(Order $order)
    {
        $order->load(['orderItems.product.category', 'user']);
        return view('admin.orders.show', compact('order'));
    }

    public function updateOrderStatus(Request $request, Order $order)
    {
        $request->validate([
            'order_status' => 'required|in:pending,confirmed,processing,shipped,delivered,cancelled',
            'payment_status' => 'required|in:pending,paid,failed,refunded'
        ]);

        $order->update([
            'order_status' => $request->order_status,
            'payment_status' => $request->payment_status
        ]);

        return redirect()->back()->with('success', 'Cập nhật trạng thái đơn hàng thành công!');
    }

    public function deleteOrder(Order $order)
    {
        $order->delete();
        return redirect()->route('admin.orders.index')->with('success', 'Đã xóa đơn hàng!');
    }

    // Quản lý user
    public function users()
    {
        $users = User::withCount('orders')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function userDetail(User $user)
    {
        $user->load(['orders' => function($query) {
            $query->orderBy('created_at', 'desc');
        }]);
        return view('admin.users.show', compact('user'));
    }

    public function userOrders(User $user)
    {
        $orders = $user->orders()->with('orderItems.product')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.users.orders', compact('user', 'orders'));
    }
}
