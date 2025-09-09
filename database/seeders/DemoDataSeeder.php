<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DemoDataSeeder extends Seeder
{
    public function run()
    {
        // Tạo user demo nếu chưa có
        $user = User::firstOrCreate([
            'email' => 'demo@example.com'
        ], [
            'name' => 'Demo User',
            'password' => bcrypt('password'),
            'role' => 'user'
        ]);

        // Tạo categories nếu chưa có
        $categories = [
            ['name' => 'Trái cây tươi', 'description' => 'Trái cây tươi ngon'],
            ['name' => 'Trái cây khô', 'description' => 'Trái cây sấy khô'],
            ['name' => 'Trái cây nhập khẩu', 'description' => 'Trái cây từ nước ngoài']
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['name' => $category['name']], $category);
        }

        // Tạo products nếu chưa có
        $categoryIds = Category::pluck('id')->toArray();
        $products = [
            ['name' => 'Táo Fuji', 'price' => 45000, 'stock' => 100],
            ['name' => 'Cam Sành', 'price' => 35000, 'stock' => 80],
            ['name' => 'Chuối Tiêu', 'price' => 25000, 'stock' => 150],
            ['name' => 'Nho Xanh', 'price' => 85000, 'stock' => 60],
            ['name' => 'Xoài Cát', 'price' => 65000, 'stock' => 40],
            ['name' => 'Dưa Hấu', 'price' => 30000, 'stock' => 20],
            ['name' => 'Dứa', 'price' => 40000, 'stock' => 50]
        ];

        foreach ($products as $product) {
            Product::firstOrCreate(['name' => $product['name']], [
                'name' => $product['name'],
                'description' => 'Mô tả cho ' . $product['name'],
                'price' => $product['price'],
                'stock' => $product['stock'],
                'category_id' => $categoryIds[array_rand($categoryIds)]
            ]);
        }

        // Tạo orders demo cho 30 ngày gần nhất
        $products = Product::all();
        $statusOptions = ['pending', 'processing', 'delivered', 'cancelled'];
        
        for ($i = 30; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $ordersPerDay = rand(1, 5); // 1-5 đơn hàng mỗi ngày
            
            for ($j = 0; $j < $ordersPerDay; $j++) {
                $order = Order::create([
                    'user_id' => $user->id,
                    'customer_name' => 'Khách hàng ' . ($j + 1),
                    'customer_phone' => '0901234567',
                    'customer_address' => 'Địa chỉ giao hàng ' . ($j + 1),
                    'order_status' => $statusOptions[array_rand($statusOptions)],
                    'payment_status' => rand(0, 1) ? 'paid' : 'pending',
                    'payment_method' => 'cod',
                    'total_amount' => 0, // Sẽ tính sau
                    'created_at' => $date,
                    'updated_at' => $date
                ]);

                // Tạo order items
                $numItems = rand(1, 4); // 1-4 sản phẩm mỗi đơn
                $totalAmount = 0;
                
                for ($k = 0; $k < $numItems; $k++) {
                    $product = $products->random();
                    $quantity = rand(1, 3);
                    $price = $product->price;
                    $subtotal = $quantity * $price;
                    
                    DB::table('order_items')->insert([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'price' => $price,
                        'created_at' => $date,
                        'updated_at' => $date
                    ]);
                    
                    $totalAmount += $subtotal;
                }

                // Cập nhật total amount
                $order->update(['total_amount' => $totalAmount]);
            }
        }

        $this->command->info('Demo data created successfully!');
    }
}
