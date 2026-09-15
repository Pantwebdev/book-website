<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    protected $table = 'payment_method';
    protected $fillable = ['payment_type', 'status', 'description'];

    public function orders()
    {
        return $this->hasMany(MasterOrder::class, 'payment_method_id');
    }


    // public function isCOD()
    // {
    //     return strtoupper($this->payment_type) === 'COD' ||
    //            stripos($this->payment_type, 'cash') !== false;
    // }
    public function isCOD()
    {
        return $this->payment_type === 'COD' || stripos($this->payment_type, 'Cash') !== false;
    }
}
