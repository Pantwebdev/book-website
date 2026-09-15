<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CheckAvailability extends Model
{
    protected $table = 'check_availabilities';


    protected $fillable = [
        'pincode',
        'status',

    ];
}
