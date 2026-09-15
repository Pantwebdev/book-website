<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class MasterOrder extends Model
{
    use HasFactory;

    protected $table = 'master_orders';

    protected $fillable = [
        'user_id',
        'guest_token',
        'order_number',
        'order_status_id',
        'payment_status_id',
        'payment_method_id',
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
        'shipping_amount',
        'grand_total',
        'razorpay_order_id',
        'razorpay_payment_id',
        'razorpay_signature',
        'payment_metadata',
        'customer_notes',
        'admin_notes',
        'payment_initiated_at',
        'payment_verified_at',
        'payment_collected_at',
        'shipped_at',
        'delivered_at',
        'cancelled_at',
        'refunded_at',
        'created_at',
        'updated_at',
        // 'status_history' को हटा दें
    ];

    protected $casts = [
        'subtotal' => 'decimal:2',
        'total_mrp' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'coupon_discount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'shipping_amount' => 'decimal:2',
        'grand_total' => 'decimal:2',
        // 'status_history' => 'array', // इसे हटा दें
        'payment_metadata' => 'array',
        'payment_initiated_at' => 'datetime',
        'payment_verified_at' => 'datetime',
        'payment_collected_at' => 'datetime',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'refunded_at' => 'datetime',
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(MasterOrderItem::class, 'master_order_id');
    }

    public function isCOD()
    {
        if ($this->paymentMethod) {
            return $this->paymentMethod->isCOD();
        }
        return false;
    }

    public function getPaymentMethodTypeAttribute()
    {
        return $this->paymentMethod ? $this->paymentMethod->payment_type : 'Unknown';
    }

    public function getOrderStatusTextAttribute()
    {
        return $this->orderStatus ? $this->orderStatus->order_status : 'Unknown';
    }

    public function getPaymentStatusTextAttribute()
    {
        return $this->paymentStatus ? $this->paymentStatus->payment_status : 'Unknown';
    }

    // नई relationships
    public function orderStatus()
    {
        return $this->belongsTo(OrderStatus::class, 'order_status_id');
    }

    public function paymentStatus()
    {
        return $this->belongsTo(PaymentStatus::class, 'payment_status_id');
    }

    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method_id');
    }

    // स्टेटस labels नई टेबल से लें
    public function getOrderStatusLabelAttribute()
    {
        return $this->orderStatus ? $this->orderStatus->order_status : 'Unknown';
    }

    public function getPaymentStatusLabelAttribute()
    {
        return $this->paymentStatus ? $this->paymentStatus->payment_status : 'Unknown';
    }

    public function getPaymentMethodLabelAttribute()
    {
        return $this->paymentMethod ? $this->paymentMethod->payment_type : 'Unknown';
    }

    // Get current status for display
    public function getCurrentOrderStatusAttribute()
    {
        return [
            'status' => $this->order_status_id,
            'label' => $this->order_status_label,
            'payment_status' => $this->payment_status_id,
            'payment_label' => $this->payment_status_label,
        ];
    }

    // Status Update Methods (अब ID के आधार पर) - SIMPLIFIED
    public function updateOrderStatus($orderStatusName)
    {
        $orderStatus = OrderStatus::where('order_status', $orderStatusName)->first();

        if ($orderStatus) {
            $this->order_status_id = $orderStatus->id;

            // Update timestamps based on status
            switch ($orderStatusName) {
                case 'Confirmed':
                    $this->confirmed_at = now();
                    break;
                case 'Shipped':
                    $this->shipped_at = now();
                    break;
                case 'Delivered':
                    $this->delivered_at = now();
                    break;
                case 'Cancelled':
                    $this->cancelled_at = now();
                    break;
                case 'Refunded':
                    $this->refunded_at = now();
                    break;
            }

            return $this->save();
        }

        return false;
    }

    public function updatePaymentStatus($paymentStatusName)
    {
        $paymentStatus = PaymentStatus::where('payment_status', $paymentStatusName)->first();

        if ($paymentStatus) {
            $this->payment_status_id = $paymentStatus->id;

            // Update timestamps
            if ($paymentStatusName == 'Success' || $paymentStatusName == 'Collected') {
                $this->payment_collected_at = now();
            } elseif ($paymentStatusName == 'Initiated') {
                $this->payment_initiated_at = now();
            }

            return $this->save();
        }

        return false;
    }

    // Helper methods
    public function isPaymentCollected()
    {
        $paymentStatus = $this->paymentStatus;
        return $paymentStatus && in_array($paymentStatus->payment_status, ['Success', 'Collected']);
    }

    public function isDelivered()
    {
        $orderStatus = $this->orderStatus;
        return $orderStatus && $orderStatus->order_status == 'Delivered';
    }

    public function isCancelled()
    {
        $orderStatus = $this->orderStatus;
        return $orderStatus && $orderStatus->order_status == 'Cancelled';
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

    public function getPaymentAmountAttribute()
    {
        return $this->grand_total * 100; // Razorpay requires amount in paise
    }

    // Mark as paid for online payments
    public function markAsPaid($razorpayPaymentId, $razorpaySignature, $paymentMetadata = null)
    {
        $this->update([
            'payment_status_id' => PaymentStatus::where('payment_status', 'Success')->first()->id,
            'order_status_id' => OrderStatus::where('order_status', 'Confirmed')->first()->id,
            'razorpay_payment_id' => $razorpayPaymentId,
            'razorpay_signature' => $razorpaySignature,
            'payment_metadata' => $paymentMetadata ? json_encode($paymentMetadata) : null,
            'payment_verified_at' => now(),
        ]);
    }

    // Generate order number
    public static function generateOrderNumber()
    {
        $prefix = 'ORD';
        $date = now()->format('ymd');

        do {
            $random = strtoupper(Str::random(6));
            $orderNumber = $prefix . $date . $random;
        } while (self::where('order_number', $orderNumber)->exists());

        return $orderNumber;
    }

    // Get all possible statuses (database से)
    public static function getOrderStatuses()
    {
        return OrderStatus::where('status', 1)->get();
    }

    public static function getPaymentStatuses()
    {
        return PaymentStatus::all();
    }

    public static function getPaymentMethods()
    {
        return PaymentMethod::all();
    }
}
