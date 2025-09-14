<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;
use App\Models\User;
use App\Models\VoucherUsage;
use App\Models\Order;

class Voucher extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'amount',
        'usage_limit',
        'usage_limit_per_user',
        'used_count',
        'minimum_order_amount',
        'starts_at',
        'expires_at',
        'is_active',
        'is_public',
        'applicable_categories',
        'applicable_users',
        'created_by',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'minimum_order_amount' => 'decimal:2',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
        'is_public' => 'boolean',
        'applicable_categories' => 'array',
        'applicable_users' => 'array',
    ];

    protected $dates = ['starts_at', 'expires_at'];

    // Relationships
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function usages(): HasMany
    {
        return $this->hasMany(VoucherUsage::class);
    }

    /**
     * Alias for usages relationship for backward compatibility
     */
    public function voucherUsages(): HasMany
    {
        return $this->usages();
    }

    /**
     * Relationship: Voucher belongs to many categories
     */
    public function categories(): BelongsToMany
    {
        return $this->belongsToMany(Category::class, 'voucher_categories');
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopePublic($query)
    {
        return $query->where('is_public', true);
    }

    public function scopeValid($query)
    {
        $now = now();
        return $query->where('is_active', true)
            ->where(function ($q) use ($now) {
                $q->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', $now);
            });
    }

    public function scopeForUser($query, $userId)
    {
        return $query->where(function ($q) use ($userId) {
            $q->whereNull('applicable_users')
              ->orWhereJsonContains('applicable_users', $userId);
        });
    }

    // Helper Methods
    public function isValid(): bool
    {
        $now = now();
        
        // Check if active
        if (!$this->is_active) {
            return false;
        }
        
        // Check start date
        if ($this->starts_at && $this->starts_at > $now) {
            return false;
        }
        
        // Check expiry date
        if ($this->expires_at && $this->expires_at < $now) {
            return false;
        }
        
        // Check usage limit
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return false;
        }
        
        return true;
    }

    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at < now();
    }

    public function isUsedUp(): bool
    {
        return $this->usage_limit && $this->used_count >= $this->usage_limit;
    }

    public function isStarted(): bool
    {
        return !$this->starts_at || $this->starts_at <= now();
    }

    public function canUserUse(User $user): bool
    {
        // Check if voucher is valid
        if (!$this->isValid()) {
            return false;
        }
        
        // Check user-specific vouchers
        if ($this->applicable_users && !in_array($user->id, $this->applicable_users)) {
            return false;
        }
        
        // Check usage limit per user
        $userUsageCount = $this->usages()->where('user_id', $user->id)->count();
        if ($userUsageCount >= $this->usage_limit_per_user) {
            return false;
        }
        
        return true;
    }

    public function calculateDiscount(float $orderAmount, array $categoryIds = []): float
    {
        // Check minimum order amount
        if ($this->minimum_order_amount && $orderAmount < $this->minimum_order_amount) {
            return 0;
        }
        
        // Check applicable categories
        if ($this->applicable_categories) {
            $hasApplicableCategory = !empty(array_intersect($categoryIds, $this->applicable_categories));
            if (!$hasApplicableCategory) {
                return 0;
            }
        }
        
        // Calculate discount
        if ($this->type === 'percentage') {
            return ($orderAmount * $this->amount) / 100;
        } else {
            return min($this->amount, $orderAmount); // Fixed amount, but not more than order total
        }
    }

    public function getDiscountText(): string
    {
        if ($this->type === 'percentage') {
            return $this->amount . '%';
        } else {
            return number_format($this->amount, 0, ',', '.') . 'đ';
        }
    }

    public function getStatusText(): string
    {
        if (!$this->is_active) {
            return 'Tạm dừng';
        }
        
        if ($this->isExpired()) {
            return 'Hết hạn';
        }
        
        if (!$this->isStarted()) {
            return 'Chưa bắt đầu';
        }
        
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return 'Đã hết lượt';
        }
        
        return 'Hoạt động';
    }

    public function getStatusColor(): string
    {
        $status = $this->getStatusText();
        
        switch ($status) {
            case 'Hoạt động':
                return 'success';
            case 'Chưa bắt đầu':
                return 'info';
            case 'Hết hạn':
            case 'Đã hết lượt':
            case 'Tạm dừng':
                return 'danger';
            default:
                return 'secondary';
        }
    }

    // Auto-generate unique voucher code
    public static function generateUniqueCode(int $length = 8): string
    {
        do {
            $code = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, $length));
        } while (static::where('code', $code)->exists());
        
        return $code;
    }
}
