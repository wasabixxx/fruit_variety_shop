<?php

namespace App\Services;

use App\Models\Voucher;
use App\Models\VoucherUsage;
use App\Models\User;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class VoucherService
{
    /**
     * Validate voucher code for a user and order
     */
    public function validateVoucher(string $code, User $user, float $orderAmount, array $categoryIds = []): array
    {
        $voucher = Voucher::where('code', $code)->first();
        
        if (!$voucher) {
            return [
                'valid' => false,
                'message' => 'Mã giảm giá không tồn tại',
                'voucher' => null,
                'discount' => 0
            ];
        }
        
        // Check if voucher is valid
        if (!$voucher->isValid()) {
            return [
                'valid' => false,
                'message' => $this->getVoucherErrorMessage($voucher),
                'voucher' => $voucher,
                'discount' => 0
            ];
        }
        
        // Check if user can use this voucher
        if (!$voucher->canUserUse($user)) {
            return [
                'valid' => false,
                'message' => 'Bạn đã sử dụng hết lượt cho mã giảm giá này',
                'voucher' => $voucher,
                'discount' => 0
            ];
        }
        
        // Calculate discount
        $discountAmount = $voucher->calculateDiscount($orderAmount, $categoryIds);
        
        if ($discountAmount <= 0) {
            $message = 'Đơn hàng không đủ điều kiện sử dụng mã giảm giá';
            
            if ($voucher->minimum_order_amount && $orderAmount < $voucher->minimum_order_amount) {
                $message = 'Đơn hàng tối thiểu ' . number_format($voucher->minimum_order_amount, 0, ',', '.') . 'đ để sử dụng mã này';
            }
            
            if ($voucher->applicable_categories && empty(array_intersect($categoryIds, $voucher->applicable_categories))) {
                $message = 'Mã giảm giá không áp dụng cho sản phẩm trong đơn hàng này';
            }
            
            return [
                'valid' => false,
                'message' => $message,
                'voucher' => $voucher,
                'discount' => 0
            ];
        }
        
        return [
            'valid' => true,
            'message' => 'Mã giảm giá hợp lệ',
            'voucher' => $voucher,
            'discount' => $discountAmount
        ];
    }
    
    /**
     * Apply voucher to an order
     */
    public function applyVoucherToOrder(Voucher $voucher, Order $order, User $user): bool
    {
        DB::beginTransaction();
        
        try {
            // Validate voucher one more time
            $categoryIds = $order->orderItems->pluck('product.category_id')->toArray();
            $validation = $this->validateVoucher(
                $voucher->code, 
                $user, 
                $order->subtotal ?: $order->total_amount, 
                $categoryIds
            );
            
            if (!$validation['valid']) {
                throw new \Exception($validation['message']);
            }
            
            $discountAmount = $validation['discount'];
            
            // Update order with voucher info
            $order->update([
                'voucher_id' => $voucher->id,
                'voucher_code' => $voucher->code,
                'discount_amount' => $discountAmount,
                'subtotal' => $order->total_amount + $discountAmount, // Store original amount
                'total_amount' => $order->total_amount - $discountAmount + $discountAmount // Recalculate
            ]);
            
            // Actually apply the discount
            $order->total_amount = $order->subtotal - $discountAmount;
            $order->save();
            
            // Record voucher usage
            VoucherUsage::create([
                'voucher_id' => $voucher->id,
                'user_id' => $user->id,
                'order_id' => $order->id,
                'order_amount' => $order->subtotal,
                'discount_amount' => $discountAmount,
                'voucher_code' => $voucher->code,
                'used_at' => now()
            ]);
            
            // Increment usage count
            $voucher->increment('used_count');
            
            DB::commit();
            return true;
            
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
    
    /**
     * Remove voucher from order
     */
    public function removeVoucherFromOrder(Order $order): bool
    {
        if (!$order->hasVoucher()) {
            return true;
        }
        
        DB::beginTransaction();
        
        try {
            $voucher = $order->voucher;
            
            // Restore original order amount
            $order->update([
                'total_amount' => $order->subtotal,
                'voucher_id' => null,
                'voucher_code' => null,
                'discount_amount' => 0,
                'subtotal' => null
            ]);
            
            // Remove usage record
            VoucherUsage::where('order_id', $order->id)->delete();
            
            // Decrement usage count
            if ($voucher) {
                $voucher->decrement('used_count');
            }
            
            DB::commit();
            return true;
            
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
    
    /**
     * Get available vouchers for a user
     */
    public function getAvailableVouchersForUser(User $user, float $orderAmount = 0, array $categoryIds = []): Collection
    {
        $query = Voucher::valid()
            ->forUser($user->id)
            ->with(['creator']);
        
        // If order amount is provided, filter by minimum order amount
        if ($orderAmount > 0) {
            $query->where(function ($q) use ($orderAmount) {
                $q->whereNull('minimum_order_amount')
                  ->orWhere('minimum_order_amount', '<=', $orderAmount);
            });
        }
        
        // If categories are provided, filter applicable categories
        if (!empty($categoryIds)) {
            $query->where(function ($q) use ($categoryIds) {
                $q->whereNull('applicable_categories');
                foreach ($categoryIds as $categoryId) {
                    $q->orWhereJsonContains('applicable_categories', $categoryId);
                }
            });
        }
        
        $vouchers = $query->orderBy('amount', 'desc')->get();
        
        // Filter out vouchers user can't use (usage limits)
        return $vouchers->filter(function ($voucher) use ($user) {
            return $voucher->canUserUse($user);
        });
    }
    
    /**
     * Get public vouchers (for display in frontend)
     */
    public function getPublicVouchers(int $perPage = 10): LengthAwarePaginator
    {
        return Voucher::valid()
            ->public()
            ->whereNull('applicable_users') // Only truly public vouchers
            ->orderBy('amount', 'desc')
            ->paginate($perPage);
    }
    
    /**
     * Create a new voucher
     */
    public function createVoucher(array $data, User $creator): Voucher
    {
        // Generate code if not provided
        if (empty($data['code'])) {
            $data['code'] = Voucher::generateUniqueCode();
        } else {
            // Ensure code is unique and uppercase
            $data['code'] = strtoupper(trim($data['code']));
            if (Voucher::where('code', $data['code'])->exists()) {
                throw ValidationException::withMessages([
                    'code' => 'Mã voucher đã tồn tại'
                ]);
            }
        }
        
        $data['created_by'] = $creator->id;
        
        // Validate dates
        if (!empty($data['starts_at']) && !empty($data['expires_at'])) {
            $startsAt = Carbon::parse($data['starts_at']);
            $expiresAt = Carbon::parse($data['expires_at']);
            
            if ($expiresAt <= $startsAt) {
                throw ValidationException::withMessages([
                    'expires_at' => 'Ngày hết hạn phải sau ngày bắt đầu'
                ]);
            }
        }
        
        return Voucher::create($data);
    }
    
    /**
     * Update voucher
     */
    public function updateVoucher(Voucher $voucher, array $data): Voucher
    {
        // Don't allow changing code if voucher has been used
        if ($voucher->used_count > 0 && isset($data['code']) && $data['code'] !== $voucher->code) {
            throw ValidationException::withMessages([
                'code' => 'Không thể thay đổi mã voucher đã được sử dụng'
            ]);
        }
        
        // Validate code uniqueness if changing
        if (isset($data['code']) && $data['code'] !== $voucher->code) {
            $data['code'] = strtoupper(trim($data['code']));
            if (Voucher::where('code', $data['code'])->where('id', '!=', $voucher->id)->exists()) {
                throw ValidationException::withMessages([
                    'code' => 'Mã voucher đã tồn tại'
                ]);
            }
        }
        
        // Validate dates
        if (!empty($data['starts_at']) && !empty($data['expires_at'])) {
            $startsAt = Carbon::parse($data['starts_at']);
            $expiresAt = Carbon::parse($data['expires_at']);
            
            if ($expiresAt <= $startsAt) {
                throw ValidationException::withMessages([
                    'expires_at' => 'Ngày hết hạn phải sau ngày bắt đầu'
                ]);
            }
        }
        
        $voucher->update($data);
        return $voucher->fresh();
    }
    
    /**
     * Get voucher usage statistics
     */
    public function getVoucherStats(Voucher $voucher): array
    {
        $usages = $voucher->usages()->with(['user', 'order'])->get();
        
        return [
            'total_uses' => $usages->count(),
            'unique_users' => $usages->pluck('user_id')->unique()->count(),
            'total_discount_given' => $usages->sum('discount_amount'),
            'total_order_value' => $usages->sum('order_amount'),
            'average_order_value' => $usages->avg('order_amount'),
            'usage_by_date' => $usages->groupBy(function ($usage) {
                return $usage->used_at->format('Y-m-d');
            })->map->count(),
            'recent_usages' => $usages->sortByDesc('used_at')->take(10)
        ];
    }
    
    /**
     * Get error message for invalid voucher
     */
    private function getVoucherErrorMessage(Voucher $voucher): string
    {
        if (!$voucher->is_active) {
            return 'Mã giảm giá đã bị tạm dừng';
        }
        
        if ($voucher->isExpired()) {
            return 'Mã giảm giá đã hết hạn';
        }
        
        if (!$voucher->isStarted()) {
            return 'Mã giảm giá chưa có hiệu lực';
        }
        
        if ($voucher->usage_limit && $voucher->used_count >= $voucher->usage_limit) {
            return 'Mã giảm giá đã hết lượt sử dụng';
        }
        
        return 'Mã giảm giá không hợp lệ';
    }
    
    /**
     * Bulk create vouchers (for mass campaigns)
     */
    public function bulkCreateVouchers(array $baseData, int $quantity, User $creator): Collection
    {
        $vouchers = collect();
        
        DB::beginTransaction();
        
        try {
            for ($i = 0; $i < $quantity; $i++) {
                $data = $baseData;
                $data['code'] = Voucher::generateUniqueCode();
                $data['name'] = $baseData['name'] . ' #' . ($i + 1);
                $data['created_by'] = $creator->id;
                
                $vouchers->push(Voucher::create($data));
            }
            
            DB::commit();
            return $vouchers;
            
        } catch (\Exception $e) {
            DB::rollback();
            throw $e;
        }
    }
    
    /**
     * Disable expired vouchers (can be run via cron)
     */
    public function disableExpiredVouchers(): int
    {
        return Voucher::where('is_active', true)
            ->where('expires_at', '<', now())
            ->update(['is_active' => false]);
    }
}