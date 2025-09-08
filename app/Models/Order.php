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
        'payment_method',
        'payment_status',
        'order_status',
        'momo_transaction_id',
        'note',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Relationship with User (if user is logged in)
    public function user()
    {
        return $this->belongsTo(User::class);
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
}
