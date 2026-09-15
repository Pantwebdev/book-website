<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subcategory extends Model
{
    protected $table = 'subcategories';
    protected $fillable = [
        'title',
        'slug',
        'category_id',
        'image',

        'description',
        'status',
    ];


    public function parentCategory()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }


    public function childSubcategories()
    {
        return $this->hasMany(ChildSubcategory::class, 'sub_category_id');
    }

}
