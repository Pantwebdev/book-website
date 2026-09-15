<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = [
        'user_id', 'guest_token', 'product_id', 'name', 'sku','qty', 'price', 'mrp_price',
        'discount', 'color', 'size', 'image','status', 'order_id',
    ];

    // Default values
    protected $attributes = [
        'status' => 'active', // active, ordered, saved, removed
    ];

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeOrdered($query)
    {
        return $query->where('status', 'ordered');
    }

    public function scopeSaved($query)
    {
        return $query->where('status', 'saved');
    }

    public function scopeGuest($query, $token)
    {
        return $query->where('guest_token', $token);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function masterOrder()
    {
        return $this->belongsTo(MasterOrder::class, 'master_order_id');
    }

    // Calculate item total with taxes
    public function getItemTotalWithTax()
    {
        $subtotal = $this->price * $this->qty;
        $taxAmount = $this->calculateTaxAmount($subtotal);
        return $subtotal + $taxAmount;
    }

    // Calculate tax amount for this item
    public function calculateTaxAmount($amount)
    {
        $gstTax = Tax::getByType(Tax::TYPE_GST);
        $otherTax = Tax::getByType(Tax::TYPE_OTHER);

        $totalTaxPercent = 0;
        if ($gstTax) {
            $totalTaxPercent += $gstTax->tax;
        }
        if ($otherTax) {
            $totalTaxPercent += $otherTax->tax;
        }

        return ($amount * $totalTaxPercent) / 100;
    }

    // ✅ Check if item is in active cart
    public function isInCart()
    {
        return $this->status === 'active';
    }

    // ✅ Check if item is ordered
    public function isOrdered()
    {
        return $this->status === 'ordered';
    }
}
