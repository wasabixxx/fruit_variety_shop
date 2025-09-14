<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $this->call(AdminSeeder::class);
        
        
        // Tạo dữ liệu đơn hàng mẫu
        $this->call(OrderSeeder::class);
        
        // Tạo dữ liệu voucher mẫu
        $this->call(VoucherSeeder::class);
    }
}