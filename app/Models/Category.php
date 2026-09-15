<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $table = 'categories';
    protected $fillable = ['title', 'image', 'meta_title', 'meta_description', 'status'];
    //  public function subcategories()
    // {
    //     return $this->hasMany(Subcategory::class, 'category');
    // }
    // Correct:
    public function subcategories()
    {
        return $this->hasMany(Subcategory::class, 'category_id'); // ✅
    }

}
