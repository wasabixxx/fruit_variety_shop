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
        
        Category::create(['name' => 'Cây ăn quả nhiệt đới', 'description' => 'Xoài, chuối, v.v.']);
        Category::create(['name' => 'Cây ăn quả ôn đới', 'description' => 'Táo, lê, v.v.']);
        Category::create(['name' => 'Cây ăn quả địa phương', 'description' => 'Thanh long, vải, v.v.']);
        
        Product::create([
            'name' => 'Hạt giống xoài Thái', 
            'category_id' => 1, 
            'price' => 50000, 
            'description' => 'Hạt giống xoài chất lượng cao', 
            'stock' => 100
        ]);
        Product::create([
            'name' => 'Hạt giống táo Fuji', 
            'category_id' => 2, 
            'price' => 60000, 
            'description' => 'Hạt giống táo nhập khẩu', 
            'stock' => 50
        ]);
        Product::create([
            'name' => 'Hạt giống chuối Laba', 
            'category_id' => 1, 
            'price' => 35000, 
            'description' => 'Hạt giống chuối ngon', 
            'stock' => 80
        ]);
        Product::create([
            'name' => 'Hạt giống thanh long ruột đỏ', 
            'category_id' => 3, 
            'price' => 75000, 
            'description' => 'Hạt giống thanh long cao cấp', 
            'stock' => 60
        ]);
        Product::create([
            'name' => 'Hạt giống vải thiều', 
            'category_id' => 3, 
            'price' => 45000, 
            'description' => 'Hạt giống vải đặc sản', 
            'stock' => 70
        ]);
        
        // Tạo dữ liệu đơn hàng mẫu
        $this->call(OrderSeeder::class);
    }
}