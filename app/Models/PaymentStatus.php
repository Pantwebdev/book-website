<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentStatus extends Model
{
    protected $table = 'payment_status';
    protected $fillable = ['payment_status', 'applicable_for', 'description'];

    public function orders()
    {
        return $this->hasMany(MasterOrder::class, 'payment_status_id');
    }
}
