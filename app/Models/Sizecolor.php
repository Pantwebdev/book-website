<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sizecolor extends Model
{
    protected $table = 'sizecolors';
    protected $fillable = ['name', 'image', 'color_id', 'type', 'status'];
}
