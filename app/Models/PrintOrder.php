<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PrintOrder extends Model
{
    use HasFactory;

    // protected $fillable = [
    //     'user_id',
    //     'guest_token',
    //     'name',
    //     'email',
    //     'phone',
    //     'file_path',
    //     'paper_size',
    //     'print_type',
    //     'copies',
    //     'pages',
    //     'total_amount',
    //     'paid_amount',
    //     'remaining_amount',
    //     'payment_status',
    //     'razorpay_order_id',
    //     'razorpay_payment_id',
    //     'razorpay_signature',
    // ];

    protected $fillable = [
        'user_id','guest_token','order_number',
        'name','email','phone','file_path',
        'paper_size','print_type','copies','pages',
        'total_amount','paid_amount','remaining_amount',
        'payment_status','payment_mode',
        'remaining_payment_mode','remaining_paid_at',
        'razorpay_order_id','razorpay_payment_id','razorpay_signature',
    ];
    protected $casts = [
        'copies' => 'integer',
        'pages' => 'integer',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'user_id');
    }
}
