<?php

// app/Models/Order.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'guest_token',
        'order_number',
        'order_status',
        'first_name',
        'last_name',
        'email',
        'phone',
        'address',
        'address2',
        'city',
        'state',
        'pincode',
        'country',
        'shipping_first_name',
        'shipping_last_name',
        'shipping_address',
        'shipping_city',
        'shipping_state',
        'shipping_pincode',
        'shipping_phone',
        'subtotal',
        'total_mrp',
        'discount_amount',
        'coupon_discount',
        'coupon_code',
        'tax_amount',
        'shipping_charge',
        'grand_total',
        'payment_method',
        'payment_status',
        'transaction_id',
        'payment_response',
        'customer_notes',
        'admin_notes',
        'confirmed_at',
        'shipped_at',
        'delivered_at',
        'cancelled_at',
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'total_mrp' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'coupon_discount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_charge' => 'decimal:2',
        'grand_total' => 'decimal:2',
        'confirmed_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('order_status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('order_status', 'confirmed');
    }

    public function scopeProcessing($query)
    {
        return $query->where('order_status', 'processing');
    }

    public function scopeShipped($query)
    {
        return $query->where('order_status', 'shipped');
    }

    public function scopeDelivered($query)
    {
        return $query->where('order_status', 'delivered');
    }

    public function scopeCancelled($query)
    {
        return $query->where('order_status', 'cancelled');
    }

    // Helper methods
    public function isPending()
    {
        return $this->order_status === 'pending';
    }

    public function isConfirmed()
    {
        return $this->order_status === 'confirmed';
    }

    public function isDelivered()
    {
        return $this->order_status === 'delivered';
    }

    public function isCancelled()
    {
        return $this->order_status === 'cancelled';
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getShippingFullNameAttribute()
    {
        if ($this->shipping_first_name) {
            return $this->shipping_first_name . ' ' . $this->shipping_last_name;
        }
        return $this->first_name . ' ' . $this->last_name;
    }

    public function getFormattedGrandTotalAttribute()
    {
        return '₹' . number_format($this->grand_total, 2);
    }

    // Generate order number
    public static function generateOrderNumber()
    {
        $prefix = 'ORD';
        $timestamp = now()->format('YmdHis');
        $random = mt_rand(1000, 9999);

        return $prefix . $timestamp . $random;
    }
}
