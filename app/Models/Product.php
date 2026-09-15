<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'sub_category_id',
        'child_sub_category_id',
        'sku',
        'name',
        'slug',
        'language',
        'edition',
        'published_date',
        'display_price',
        'mrp_price',
        'discount',
        'stock',
        'author',
        'publisher',
        'product_on_sale',
        'new_arrivals',
        'exam_corner',
        'featured_book',
        'description',
        'image',
        'image2',
        'image3',
        'image4',
        'image5',
        'multipleimage',
        'altimage',
        'altimage2',
        'altimage3',
        'altimage4',
        'altimage5',
        'product_pdf',
        'shipping_type',
        'shipping_charge',
        'cod_available',

    ];

    protected $casts = [

        'multipleimage' => 'array',

    ];

    // Auto-generate slug if not set
    protected static function boot()
    {
        parent::boot();
        static::creating(function ($product) {
            if (!$product->slug) {
                $product->slug = Str::slug($product->name);
            }
        });
    }

    // Product.php
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class, 'sub_category_id');
    }

    public function childsubcategory()
    {
        
        return $this->belongsTo(ChildSubcategory::class, 'child_sub_category_id');
    }

    public function discountCodes()
    {
        return $this->belongsToMany(DiscountCode::class, 'product_discount');
    }

    public function setColorsAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['colors'] = json_encode(array_map('intval', $value));
        } else {
            $this->attributes['colors'] = $value;
        }
    }
    // Add this method to Product model
    public function seo()
    {
        return $this->hasOne(Seo::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function decreaseStock($quantity)
    {
        if ($this->stock >= $quantity) {
            $this->decrement('stock', $quantity);
            return true;
        }
        // अगर stock कम है तो exception throw कर सकते हैं
        throw new \Exception("Insufficient stock for product: {$this->name}");
    }
}
