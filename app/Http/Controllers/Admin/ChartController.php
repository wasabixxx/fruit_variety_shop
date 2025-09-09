<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class ChartController extends Controller
{
    public function dailyRevenue(Request $request)
    {
        $days = $request->get('days', 7);
        
        $data = Order::select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(total_amount) as total'),
                DB::raw('COUNT(*) as orders_count')
            )
            ->where('payment_status', 'paid')
            ->where('created_at', '>=', Carbon::now()->subDays($days))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        // Format data cho Chart.js
        $labels = [];
        $values = [];
        
        // Tạo mảng cho 7 ngày với giá trị mặc định 0
        for ($i = $days - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i)->format('Y-m-d');
            $labels[] = Carbon::now()->subDays($i)->format('d/m');
            
            $found = $data->firstWhere('date', $date);
            $values[] = $found ? (float)$found->total : 0;
        }

        return response()->json([
            'labels' => $labels,
            'data' => $values
        ]);
    }

    public function monthlyRevenue(Request $request)
    {
        $months = $request->get('months', 6);
        
        $data = Order::select(
                DB::raw('YEAR(created_at) as year'),
                DB::raw('MONTH(created_at) as month'),
                DB::raw('SUM(total_amount) as total')
            )
            ->where('payment_status', 'paid')
            ->where('created_at', '>=', Carbon::now()->subMonths($months))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();

        // Format data cho Chart.js
        $labels = [];
        $values = [];
        
        for ($i = $months - 1; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $labels[] = 'T' . $date->format('n') . '/' . $date->format('Y');
            
            $found = $data->where('year', $date->year)->where('month', $date->month)->first();
            $values[] = $found ? (float)$found->total : 0;
        }

        return response()->json([
            'labels' => $labels,
            'data' => $values
        ]);
    }

    public function ordersByStatus()
    {
        $data = Order::select('order_status', DB::raw('count(*) as total'))
            ->groupBy('order_status')
            ->get();

        // Mapping trạng thái tiếng Việt
        $statusMap = [
            'pending' => 'Chờ xử lý',
            'processing' => 'Đang xử lý', 
            'shipped' => 'Đã giao',
            'delivered' => 'Đã nhận',
            'cancelled' => 'Đã hủy'
        ];

        $labels = [];
        $values = [];
        
        foreach ($data as $item) {
            $labels[] = $statusMap[$item->order_status] ?? $item->order_status;
            $values[] = (int)$item->total;
        }

        return response()->json([
            'labels' => $labels,
            'data' => $values
        ]);
    }

    public function topProducts(Request $request)
    {
        $limit = $request->get('limit', 5);
        
        $data = DB::table('order_items')
            ->join('products', 'order_items.product_id', '=', 'products.id')
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.order_status', '!=', 'cancelled')
            ->select('products.name', DB::raw('SUM(order_items.quantity) as total_sold'))
            ->groupBy('products.id', 'products.name')
            ->orderBy('total_sold', 'desc')
            ->limit($limit)
            ->get();

        $labels = [];
        $values = [];
        
        foreach ($data as $item) {
            $labels[] = $item->name;
            $values[] = (int)$item->total_sold;
        }

        // Nếu không có dữ liệu, trả về dữ liệu mẫu
        if (empty($labels)) {
            $labels = ['Táo Fuji', 'Cam Sành', 'Chuối Tiêu', 'Nho Xanh', 'Xoài Cát'];
            $values = [0, 0, 0, 0, 0]; // Sẽ hiển thị 0 nếu chưa có data
        }

        return response()->json([
            'labels' => $labels,
            'data' => $values
        ]);
    }

    public function categoryStats()
    {
        $data = Category::withCount(['products' => function($query) {
                $query->where('stock', '>', 0);
            }])
            ->get();

        $labels = [];
        $values = [];
        
        foreach ($data as $category) {
            $labels[] = $category->name;
            $values[] = (int)$category->products_count;
        }

        // Nếu không có dữ liệu, trả về dữ liệu mẫu
        if (empty($labels)) {
            $labels = ['Trái cây tươi', 'Trái cây khô', 'Trái cây nhập khẩu'];
            $values = [0, 0, 0];
        }

        return response()->json([
            'labels' => $labels,
            'data' => $values
        ]);
    }

    public function statistics()
    {
        $today = Carbon::now()->format('Y-m-d');
        $weekStart = Carbon::now()->startOfWeek();
        $monthStart = Carbon::now()->startOfMonth();
        
        // Doanh thu hôm nay
        $todayRevenue = Order::where('payment_status', 'paid')
            ->whereDate('created_at', $today)
            ->sum('total_amount');
            
        // Doanh thu tuần này
        $weekRevenue = Order::where('payment_status', 'paid')
            ->where('created_at', '>=', $weekStart)
            ->sum('total_amount');
            
        // Doanh thu tháng này
        $monthRevenue = Order::where('payment_status', 'paid')
            ->where('created_at', '>=', $monthStart)
            ->sum('total_amount');
            
        // Tổng doanh thu
        $totalRevenue = Order::where('payment_status', 'paid')
            ->sum('total_amount');
            
        // Đơn hàng hôm nay
        $todayOrders = Order::whereDate('created_at', $today)->count();
        
        // Đơn hàng tuần này
        $weekOrders = Order::where('created_at', '>=', $weekStart)->count();
        
        // Đơn hàng tháng này
        $monthOrders = Order::where('created_at', '>=', $monthStart)->count();
        
        // Tổng đơn hàng
        $totalOrders = Order::count();
        
        return response()->json([
            'revenue' => [
                'today' => number_format($todayRevenue, 0, ',', '.') . '₫',
                'week' => number_format($weekRevenue, 0, ',', '.') . '₫',
                'month' => number_format($monthRevenue, 0, ',', '.') . '₫',
                'total' => number_format($totalRevenue, 0, ',', '.') . '₫'
            ],
            'orders' => [
                'today' => $todayOrders,
                'week' => $weekOrders,
                'month' => $monthOrders,
                'total' => $totalOrders
            ]
        ]);
    }
}
