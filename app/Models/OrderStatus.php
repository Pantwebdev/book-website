<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderStatus extends Model
{
    protected $table = 'order_status';
    protected $fillable = ['order_status', 'remark', 'status'];

    public function orders()
    {
        return $this->hasMany(MasterOrder::class, 'order_status_id');
    }
}
