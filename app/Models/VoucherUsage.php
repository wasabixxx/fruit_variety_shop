<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VoucherUsage extends Model
{
    use HasFactory;

    protected $fillable = [
        'voucher_id',
        'user_id',
        'order_id',
        'order_amount',
        'discount_amount',
        'voucher_code',
        'used_at',
    ];

    protected $casts = [
        'order_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'used_at' => 'datetime',
    ];

    protected $dates = ['used_at'];

    // Relationships
    public function voucher(): BelongsTo
    {
        return $this->belongsTo(Voucher::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // Helper methods
    public function getSavingsPercentage(): float
    {
        if ($this->order_amount > 0) {
            return ($this->discount_amount / $this->order_amount) * 100;
        }
        
        return 0;
    }

    public function getFormattedDiscountAmount(): string
    {
        return number_format($this->discount_amount, 0, ',', '.') . 'đ';
    }

    public function getFormattedOrderAmount(): string
    {
        return number_format($this->order_amount, 0, ',', '.') . 'đ';
    }
}
