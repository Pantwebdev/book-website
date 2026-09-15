<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DiscountCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'discount_value',
        'valid_from',
        'valid_until',
        'usage_limit',
        'used_count',
        'is_active',
    ];

    protected $casts = [
        'valid_from' => 'date',
        'valid_until' => 'date',
        'is_active' => 'boolean',
    ];

    // public function products()
    // {
    //     return $this->belongsToMany(Product::class, 'product_discount');
    // }
    public function products()
    {
        return null; // or simply remove this method
    }

    public function isValid()
    {
        if (!$this->is_active) {
            return false;
        }

        $now = now();
        if ($now->lt($this->valid_from)) {
            return false;
        }
        if ($now->gt($this->valid_until)) {
            return false;
        }

        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }

    public function applyDiscount($price)
    {
        if ($this->type === 'fixed') {
            return max(0, $price - $this->discount_value);
        } else {
            return $price * (1 - ($this->discount_value / 100));
        }
    }

    public function incrementUsage()
    {
        $this->increment('used_count');
    }
}
