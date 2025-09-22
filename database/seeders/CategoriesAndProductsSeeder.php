<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;

class CategoriesAndProductsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Tạo 3 danh mục
        $categories = [
            [
                'name' => 'Trái cây tươi',
                'description' => 'Các loại trái cây tươi ngon, chất lượng cao từ các vườn uy tín',
                'image' => 'https://images.unsplash.com/photo-1619566636858-adf3ef46400b?w=800&h=600&fit=crop',
            ],
            [
                'name' => 'Trái cây nhiệt đới',
                'description' => 'Trái cây nhiệt đới đặc sản, hương vị độc đáo',
                'image' => 'https://images.unsplash.com/photo-1582979512210-99b6a53386f9?w=800&h=600&fit=crop',
            ],
            [
                'name' => 'Trái cây nhập khẩu',
                'description' => 'Trái cây cao cấp nhập khẩu từ các nước có nền nông nghiệp phát triển',
                'image' => 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=800&h=600&fit=crop',
            ]
        ];

        foreach ($categories as $categoryData) {
            Category::create($categoryData);
        }

        // Lấy ID của các danh mục vừa tạo
        $freshFruits = Category::where('name', 'Trái cây tươi')->first();
        $tropicalFruits = Category::where('name', 'Trái cây nhiệt đới')->first();
        $importedFruits = Category::where('name', 'Trái cây nhập khẩu')->first();

        // Tạo 20 sản phẩm
        $products = [
            // Trái cây tươi (8 sản phẩm)
            [
                'name' => 'Táo Fuji',
                'description' => 'Táo Fuji Nhật Bản tươi ngon, giòn ngọt, giàu vitamin C và chất xơ. Thích hợp cho mọi lứa tuổi.',
                'price' => 85000,
                'category_id' => $freshFruits->id,
                'image' => 'https://images.unsplash.com/photo-1570913149827-d2ac84ab3f9a?w=800&h=600&fit=crop',
                'stock' => 50,
                'is_featured' => true,
            ],
            [
                'name' => 'Cam sành Cao Phong',
                'description' => 'Cam sành Cao Phong đặc sản Hòa Bình, vỏ mỏng, múi to, nước ngọt thanh mát.',
                'price' => 65000,
                'category_id' => $freshFruits->id,
                'image' => 'https://images.unsplash.com/photo-1547514701-42782101795e?w=800&h=600&fit=crop',
                'stock' => 75,
                'is_featured' => false,
            ],
            [
                'name' => 'Nho xanh không hạt',
                'description' => 'Nho xanh không hạt Australia, quả to, vỏ mỏng, thịt giòn ngọt, giàu chất chống oxy hóa.',
                'price' => 120000,
                'category_id' => $freshFruits->id,
                'image' => 'https://images.unsplash.com/photo-1423483641154-5411ec9c0ddf?w=800&h=600&fit=crop',
                'stock' => 30,
                'is_featured' => true,
            ],
            [
                'name' => 'Dâu tây Đà Lạt',
                'description' => 'Dâu tây Đà Lạt tươi ngon, màu đỏ tự nhiên, vị ngọt dịu, giàu vitamin C.',
                'price' => 180000,
                'category_id' => $freshFruits->id,
                'image' => 'https://images.unsplash.com/photo-1464965911861-746a04b4bca6?w=800&h=600&fit=crop',
                'stock' => 25,
                'is_featured' => true,
            ],
            [
                'name' => 'Lê Nam Phi',
                'description' => 'Lê Nam Phi cao cấp, thịt trắng, giòn ngọt, nước nhiều, thích hợp làm salad trái cây.',
                'price' => 95000,
                'category_id' => $freshFruits->id,
                'image' => 'https://images.unsplash.com/photo-1568702846914-96b305d2aaeb?w=800&h=600&fit=crop',
                'stock' => 40,
                'is_featured' => false,
            ],
            [
                'name' => 'Chuối già Nam Mỹ',
                'description' => 'Chuối già Nam Mỹ ngọt thơm, giàu kali và vitamin B6, tốt cho sức khỏe tim mạch.',
                'price' => 45000,
                'category_id' => $freshFruits->id,
                'image' => 'https://images.unsplash.com/photo-1571771894821-ce9b6c11b08e?w=800&h=600&fit=crop',
                'stock' => 60,
                'is_featured' => false,
            ],
            [
                'name' => 'Dưa hấu không hạt',
                'description' => 'Dưa hấu không hạt Hàn Quốc, thịt đỏ, ngọt mát, bổ sung nước và vitamin A.',
                'price' => 55000,
                'category_id' => $freshFruits->id,
                'image' => 'https://images.unsplash.com/photo-1587300003388-59208cc962cb?w=800&h=600&fit=crop',
                'stock' => 35,
                'is_featured' => false,
            ],
            [
                'name' => 'Nho đỏ Ruby',
                'description' => 'Nho đỏ Ruby Mỹ, quả to màu đỏ tím, vị ngọt đậm đà, giàu resveratrol.',
                'price' => 135000,
                'category_id' => $freshFruits->id,
                'image' => 'https://images.unsplash.com/photo-1537640538966-79f369143f8f?w=800&h=600&fit=crop',
                'stock' => 28,
                'is_featured' => true,
            ],

            // Trái cây nhiệt đới (7 sản phẩm)
            [
                'name' => 'Sầu riêng Monthong',
                'description' => 'Sầu riêng Monthong Thái Lan, múi dày, vị ngọt béo, thơm đặc trưng, được mệnh danh là vua của các loại trái cây.',
                'price' => 250000,
                'category_id' => $tropicalFruits->id,
                'image' => 'https://images.unsplash.com/photo-1586190848861-99aa4a171e90?w=800&h=600&fit=crop',
                'stock' => 15,
                'is_featured' => true,
            ],
            [
                'name' => 'Măng cụt Thái Lan',
                'description' => 'Măng cụt Thái Lan tươi ngon, vỏ tím đậm, thịt trắng trong suốt, vị ngọt thanh, mát lành.',
                'price' => 180000,
                'category_id' => $tropicalFruits->id,
                'image' => 'https://images.unsplash.com/photo-1615484477778-ca3b77940c25?w=800&h=600&fit=crop',
                'stock' => 20,
                'is_featured' => true,
            ],
            [
                'name' => 'Xoài cát Hòa Lộc',
                'description' => 'Xoài cát Hòa Lộc Tiền Giang, thịt vàng đậm, ngọt thơm, ít xơ, đặc sản nổi tiếng miền Tây.',
                'price' => 85000,
                'category_id' => $tropicalFruits->id,
                'image' => 'https://images.unsplash.com/photo-1553279768-865429fa0078?w=800&h=600&fit=crop',
                'stock' => 45,
                'is_featured' => true,
            ],
            [
                'name' => 'Dứa Cayenne',
                'description' => 'Dứa Cayenne Costa Rica, thịt vàng, ngọt đậm, thơm nồng, giàu enzyme tiêu hóa.',
                'price' => 75000,
                'category_id' => $tropicalFruits->id,
                'image' => 'https://images.unsplash.com/photo-1589820296156-2454bb8a6ad1?w=800&h=600&fit=crop',
                'stock' => 35,
                'is_featured' => false,
            ],
            [
                'name' => 'Đu đủ Thái Lan',
                'description' => 'Đu đủ Thái Lan chín vàng, thịt cam đỏ, ngọt mát, giàu vitamin A và papain.',
                'price' => 55000,
                'category_id' => $tropicalFruits->id,
                'image' => 'https://images.unsplash.com/photo-1523049673857-eb18f1d7b578?w=800&h=600&fit=crop',
                'stock' => 40,
                'is_featured' => false,
            ],
            [
                'name' => 'Chôm chôm',
                'description' => 'Chôm chôm Thái Lan tươi ngon, vỏ đỏ, thịt trong suốt, vị ngọt thanh, giàu vitamin C.',
                'price' => 95000,
                'category_id' => $tropicalFruits->id,
                'image' => 'https://images.unsplash.com/photo-1561181286-d3fee7d55364?w=800&h=600&fit=crop',
                'stock' => 30,
                'is_featured' => false,
            ],
            [
                'name' => 'Vải thiều Bắc Giang',
                'description' => 'Vải thiều Bắc Giang đặc sản, vỏ đỏ mỏng, thịt trắng trong, ngọt đậm đà, thơm dịu.',
                'price' => 120000,
                'category_id' => $tropicalFruits->id,
                'image' => 'https://images.unsplash.com/photo-1591274470925-9e1896cec928?w=800&h=600&fit=crop',
                'stock' => 25,
                'is_featured' => true,
            ],

            // Trái cây nhập khẩu (5 sản phẩm)
            [
                'name' => 'Cherry Mỹ',
                'description' => 'Cherry Mỹ tươi ngon, màu đỏ tươi, vị ngọt chua hài hòa, giàu anthocyanin và vitamin C.',
                'price' => 450000,
                'category_id' => $importedFruits->id,
                'image' => 'https://images.unsplash.com/photo-1571068316344-75bc76f77890?w=800&h=600&fit=crop',
                'stock' => 15,
                'is_featured' => true,
            ],
            [
                'name' => 'Blueberry Canada',
                'description' => 'Blueberry Canada tươi, quả nhỏ màu xanh tím, vị ngọt thanh, siêu thực phẩm giàu chất chống oxy hóa.',
                'price' => 380000,
                'category_id' => $importedFruits->id,
                'image' => 'https://images.unsplash.com/photo-1498557850523-fd3d118b962e?w=800&h=600&fit=crop',
                'stock' => 20,
                'is_featured' => true,
            ],
            [
                'name' => 'Kiwi New Zealand',
                'description' => 'Kiwi New Zealand chín vừa, thịt xanh, vị chua ngọt đặc trưng, vitamin C gấp 10 lần cam.',
                'price' => 160000,
                'category_id' => $importedFruits->id,
                'image' => 'https://images.unsplash.com/photo-1585059895524-72359e06133a?w=800&h=600&fit=crop',
                'stock' => 35,
                'is_featured' => false,
            ],
            [
                'name' => 'Lựu đỏ Iran',
                'description' => 'Lựu đỏ Iran cao cấp, hạt đỏ ruby, vị ngọt thanh, giàu chất chống lão hóa.',
                'price' => 220000,
                'category_id' => $importedFruits->id,
                'image' => 'https://images.unsplash.com/photo-1559181567-c3190ca9959b?w=800&h=600&fit=crop',
                'stock' => 18,
                'is_featured' => true,
            ],
            [
                'name' => 'Bơ Hass Mexico',
                'description' => 'Bơ Hass Mexico chín vừa, thịt vàng béo ngậy, giàu omega-3 và vitamin E, tốt cho tim mạch.',
                'price' => 125000,
                'category_id' => $importedFruits->id,
                'image' => 'https://images.unsplash.com/photo-1583521214690-73421a1829a9?w=800&h=600&fit=crop',
                'stock' => 40,
                'is_featured' => false,
            ],
        ];

        foreach ($products as $productData) {
            Product::create($productData);
        }
    }
}