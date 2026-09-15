<?php

// app/Models/MasterOrderItem.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterOrderItem extends Model
{
    use HasFactory;

    protected $table = 'master_order_items';

    protected $fillable = [
        'master_order_id',
        'product_id',
        'product_name',
        'sku',
        'qty',
        'price',
        'mrp_price',
        'discount',
        'color',
        'size',
        'image',
        'item_total',
        'item_mrp_total',
        'item_discount',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'mrp_price' => 'decimal:2',
        'discount' => 'decimal:2',
        'item_total' => 'decimal:2',
        'item_mrp_total' => 'decimal:2',
        'item_discount' => 'decimal:2',
    ];

    // Relationships
    public function masterOrder()
    {
        return $this->belongsTo(MasterOrder::class, 'master_order_id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    // Helper methods
    public function getFormattedPriceAttribute()
    {
        return '₹' . number_format($this->price, 2);
    }

    public function getFormattedMrpAttribute()
    {
        return '₹' . number_format($this->mrp_price, 2);
    }

    public function getFormattedItemTotalAttribute()
    {
        return '₹' . number_format($this->item_total, 2);
    }

    // Calculate item totals
    public function calculateTotals()
    {
        $this->item_total = $this->price * $this->qty;
        $this->item_mrp_total = $this->mrp_price * $this->qty;
        $this->item_discount = $this->item_mrp_total - $this->item_total;

        return $this;
    }
}
