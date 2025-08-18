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
    }
}