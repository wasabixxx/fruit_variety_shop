<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Voucher;
use Carbon\Carbon;

class VoucherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $vouchers = [
            // Voucher giảm theo phần trăm
            [
                'code' => 'WELCOME20',
                'name' => 'Chào mừng khách hàng mới',
                'description' => 'Giảm 20% cho đơn hàng đầu tiên từ 200.000đ',
                'type' => 'percentage',
                'amount' => 20,
                'minimum_order_amount' => 200000,
                'usage_limit' => 100,
                'expires_at' => Carbon::now()->addMonths(2),
                'is_active' => true,
                'created_by' => 1, // Admin user ID
            ],
            [
                'code' => 'SUMMER15',
                'name' => 'Khuyến mãi mùa hè',
                'description' => 'Giảm 15% cho tất cả đơn hàng từ 150.000đ',
                'type' => 'percentage',
                'amount' => 15,
                'minimum_order_amount' => 150000,
                'usage_limit' => 200,
                'expires_at' => Carbon::now()->addMonths(1),
                'is_active' => true,
                'created_by' => 1,
            ],
            [
                'code' => 'FRUIT10',
                'name' => 'Yêu trái cây',
                'description' => 'Giảm 10% cho đơn hàng trái cây tươi',
                'type' => 'percentage',
                'amount' => 10,
                'minimum_order_amount' => 100000,
                'usage_limit' => 500,
                'expires_at' => Carbon::now()->addWeeks(3),
                'is_active' => true,
                'created_by' => 1,
            ],

            // Voucher giảm cố định
            [
                'code' => 'SAVE50K',
                'name' => 'Tiết kiệm 50K',
                'description' => 'Giảm 50.000đ cho đơn hàng từ 300.000đ',
                'type' => 'fixed',
                'amount' => 50000,
                'minimum_order_amount' => 300000,
                'usage_limit' => 150,
                'expires_at' => Carbon::now()->addMonth(),
                'is_active' => true,
                'created_by' => 1,
            ],
            [
                'code' => 'DISCOUNT30K',
                'name' => 'Giảm ngay 30K',
                'description' => 'Giảm 30.000đ cho đơn hàng từ 200.000đ',
                'type' => 'fixed',
                'amount' => 30000,
                'minimum_order_amount' => 200000,
                'usage_limit' => 300,
                'expires_at' => Carbon::now()->addWeeks(2),
                'is_active' => true,
                'created_by' => 1,
            ],
            [
                'code' => 'FREESHIP',
                'name' => 'Miễn phí vận chuyển',
                'description' => 'Giảm 20.000đ phí ship cho đơn từ 100.000đ',
                'type' => 'fixed',
                'amount' => 20000,
                'minimum_order_amount' => 100000,
                'usage_limit' => 1000,
                'expires_at' => Carbon::now()->addMonths(3),
                'is_active' => true,
                'created_by' => 1,
            ],

            // Voucher đặc biệt
            [
                'code' => 'VIP25',
                'name' => 'Ưu đãi VIP',
                'description' => 'Giảm 25% cho khách hàng VIP (tối đa 100K)',
                'type' => 'percentage',
                'amount' => 25,
                'minimum_order_amount' => 500000,
                'usage_limit' => 50,
                'expires_at' => Carbon::now()->addMonths(6),
                'is_active' => true,
                'created_by' => 1,
            ],
            [
                'code' => 'FLASH100K',
                'name' => 'Flash Sale',
                'description' => 'Giảm ngay 100.000đ cho đơn hàng từ 800.000đ',
                'type' => 'fixed',
                'amount' => 100000,
                'minimum_order_amount' => 800000,
                'usage_limit' => 20,
                'expires_at' => Carbon::now()->addDays(7),
                'is_active' => true,
                'created_by' => 1,
            ],

            // Voucher sắp hết hạn để test
            [
                'code' => 'LASTCHANCE',
                'name' => 'Cơ hội cuối',
                'description' => 'Giảm 30% - Chỉ còn 3 ngày!',
                'type' => 'percentage',
                'amount' => 30,
                'minimum_order_amount' => 250000,
                'usage_limit' => 30,
                'expires_at' => Carbon::now()->addDays(3),
                'is_active' => true,
                'created_by' => 1,
            ],

            // Voucher đã hết hạn để test
            [
                'code' => 'EXPIRED20',
                'name' => 'Voucher đã hết hạn',
                'description' => 'Voucher này đã hết hạn sử dụng',
                'type' => 'percentage',
                'amount' => 20,
                'minimum_order_amount' => 100000,
                'usage_limit' => 100,
                'expires_at' => Carbon::now()->subDays(5),
                'is_active' => false,
                'created_by' => 1,
            ],
        ];

        foreach ($vouchers as $voucherData) {
            Voucher::create($voucherData);
        }

        $this->command->info('✅ Đã tạo ' . count($vouchers) . ' voucher mẫu thành công!');
    }
}
