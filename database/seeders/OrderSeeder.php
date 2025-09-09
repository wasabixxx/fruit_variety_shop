<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use App\Models\Product;
use Carbon\Carbon;
use Faker\Factory as Faker;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('vi_VN');
        
        // Lấy tất cả sản phẩm
        $products = Product::all();
        
        if ($products->isEmpty()) {
            $this->command->info('Không có sản phẩm nào. Hãy chạy ProductSeeder trước.');
            return;
        }

        // Tạo đơn hàng cho 3 tháng gần nhất
        for ($i = 90; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            
            // Tạo 1-5 đơn hàng mỗi ngày
            $orderCount = rand(1, 5);
            
            for ($j = 0; $j < $orderCount; $j++) {
                $order = Order::create([
                    'customer_name' => $faker->name,
                    'customer_phone' => $faker->phoneNumber,
                    'customer_address' => $faker->address,
                    'total_amount' => 0, // Sẽ tính sau
                    'payment_method' => $faker->randomElement(['cod', 'momo_atm', 'momo_card', 'momo_wallet']),
                    'payment_status' => $faker->randomElement(['pending', 'paid', 'failed']),
                    'order_status' => $faker->randomElement(['pending', 'confirmed', 'processing', 'shipped', 'delivered', 'cancelled']),
                    'created_at' => $date->addHours(rand(8, 20))->addMinutes(rand(0, 59)),
                    'updated_at' => $date->addHours(rand(8, 20))->addMinutes(rand(0, 59)),
                ]);

                // Thêm 1-4 sản phẩm vào đơn hàng
                $itemCount = rand(1, 4);
                $totalAmount = 0;
                
                $selectedProducts = $products->random($itemCount);
                
                foreach ($selectedProducts as $product) {
                    $quantity = rand(1, 3);
                    $price = $product->price;
                    
                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $product->id,
                        'quantity' => $quantity,
                        'price' => $price
                    ]);
                    
                    $totalAmount += $price * $quantity;
                }
                
                // Cập nhật tổng tiền đơn hàng
                $order->update(['total_amount' => $totalAmount]);
            }
        }

        $this->command->info('Đã tạo ' . Order::count() . ' đơn hàng mẫu.');
    }
}
