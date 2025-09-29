<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'customer_name',
        'customer_phone',
        'customer_address',
        'total_amount',
        'shipping_fee',
        'voucher_id',
        'voucher_code',
        'discount_amount',
        'subtotal',
        'payment_method',
        'payment_status',
        'order_status',
        'momo_transaction_id',
        'note',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'shipping_fee' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationship with User (if user is logged in)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Voucher relationship
    public function voucher()
    {
        return $this->belongsTo(Voucher::class);
    }

    public function voucherUsage()
    {
        return $this->hasOne(VoucherUsage::class);
    }

    // Relationship with OrderItems
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Status constants
    const STATUS_PENDING = 'pending';
    const STATUS_CONFIRMED = 'confirmed';
    const STATUS_PROCESSING = 'processing';
    const STATUS_SHIPPED = 'shipped';
    const STATUS_DELIVERED = 'delivered';
    const STATUS_CANCELLED = 'cancelled';

    const PAYMENT_STATUS_PENDING = 'pending';
    const PAYMENT_STATUS_PAID = 'paid';
    const PAYMENT_STATUS_FAILED = 'failed';
    const PAYMENT_STATUS_REFUNDED = 'refunded';

    public function getStatusLabelAttribute()
    {
        $statuses = [
            self::STATUS_PENDING => 'Chờ xử lý',
            self::STATUS_CONFIRMED => 'Đã xác nhận',
            self::STATUS_PROCESSING => 'Đang xử lý',
            self::STATUS_SHIPPED => 'Đang giao hàng',
            self::STATUS_DELIVERED => 'Đã giao hàng',
            self::STATUS_CANCELLED => 'Đã hủy',
        ];
        return $statuses[$this->order_status] ?? 'Không xác định';
    }

    public function getPaymentStatusLabelAttribute()
    {
        $statuses = [
            self::PAYMENT_STATUS_PENDING => 'Chờ thanh toán',
            self::PAYMENT_STATUS_PAID => 'Đã thanh toán',
            self::PAYMENT_STATUS_FAILED => 'Thanh toán thất bại',
            self::PAYMENT_STATUS_REFUNDED => 'Đã hoàn tiền',
        ];
        return $statuses[$this->payment_status] ?? 'Không xác định';
    }

    // Voucher helper methods
    public function hasVoucher(): bool
    {
        return $this->voucher_id !== null;
    }

    public function getDiscountPercentage(): float
    {
        if ($this->subtotal > 0 && $this->discount_amount > 0) {
            return ($this->discount_amount / $this->subtotal) * 100;
        }
        
        return 0;
    }

    public function getFormattedDiscount(): string
    {
        return number_format($this->discount_amount, 0, ',', '.') . 'đ';
    }

    public function getFormattedSubtotal(): string
    {
        return number_format($this->subtotal, 0, ',', '.') . 'đ';
    }

    public function getFormattedShippingFee(): string
    {
        return number_format($this->shipping_fee, 0, ',', '.') . 'đ';
    }
    
    public function getFormattedTotal(): string
    {
        return number_format($this->total_amount, 0, ',', '.') . 'đ';
    }
}
