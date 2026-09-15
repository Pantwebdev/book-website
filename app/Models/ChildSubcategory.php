<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ChildSubcategory extends Model
{
    protected $table = 'child_subcategories';
    protected $fillable = [
        'title',
        'slug',
        'category_id',
        'sub_category_id',
        'image',
        'description',
        'show_collection',
        'collection_image',
        'status',
    ];

    //    public function category()
    //     {
    //         return $this->belongsTo(Category::class, 'category_id');
    //     }

    //     public function subcategory()
    //     {
    //         return $this->belongsTo(Subcategory::class, 'sub_category_id');
    //     }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function subcategory()
    {
        return $this->belongsTo(Subcategory::class, 'sub_category_id');
    }

}
