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
        $totalCategories = Category::count();
        $totalProducts = Product::count();
        $totalUsers = User::where('role', 'user')->count();
        $totalOrders = Order::count();
        
        $recentProducts = Product::with('category')->latest()->take(5)->get();
        $recentOrders = Order::with(['orderItems.product', 'user'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $ordersByStatus = Order::selectRaw('order_status, COUNT(*) as count')
            ->groupBy('order_status')
            ->pluck('count', 'order_status');

        return view('admin.dashboard', compact(
            'totalCategories',
            'totalProducts', 
            'totalUsers',
            'totalOrders',
            'recentProducts',
            'recentOrders',
            'ordersByStatus'
        ));
    }

    // Quản lý đơn hàng
    public function orders(Request $request)
    {
        $query = Order::with(['orderItems.product', 'user']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('order_status', $request->status);
        }

        // Filter by payment method
        if ($request->filled('payment_method')) {
            $query->where('payment_method', $request->payment_method);
        }

        // Search by customer name or phone
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        $orders = $query->orderBy('created_at', 'desc')->paginate(15);

        return view('admin.orders.index', compact('orders'));
    }

    public function orderDetail(Order $order)
    {
        $order->load(['orderItems.product', 'user']);
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
        return redirect()->route('admin.orders')->with('success', 'Đã xóa đơn hàng!');
    }

    // Quản lý user
    public function users()
    {
        $users = User::withCount('orders')->orderBy('created_at', 'desc')->paginate(20);
        return view('admin.users.index', compact('users'));
    }

    public function userOrders(User $user)
    {
        $orders = $user->orders()->with('orderItems.product')->orderBy('created_at', 'desc')->paginate(10);
        return view('admin.users.orders', compact('user', 'orders'));
    }
}
