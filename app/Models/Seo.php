<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seo extends Model
{
    protected $table = 'seos';

    protected $fillable = [
        'product_id', // Add this field
        'meta_slug',
        'meta_title',
        'canonical_url',
        'image_url',
        'meta_keyword',
        'meta_description',
    ];

    // Add relationship to Product
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
