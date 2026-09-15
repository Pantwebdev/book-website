<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderHistory extends Model
{
    protected $fillable = [
        'master_order_id', 'type', 'old_status', 'new_status', 'notes', 'changed_by',
    ];

    public function order()
    {
        return $this->belongsTo(MasterOrder::class, 'master_order_id');
    }

    public function changedBy()
    {
        return $this->belongsTo(User::class, 'changed_by');
    }
}
