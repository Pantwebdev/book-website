<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SimplePage extends Model
{
    protected $table = 'simple_page';
    protected $fillable = ['title', 'slug', 'image', 'short_content', 'content', 'alt_tag', 'status'];
}
