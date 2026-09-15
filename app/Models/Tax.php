<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tax extends Model
{
    use HasFactory;
    protected $table = 'taxes';

    protected $fillable = [
        'type',
        'tax',
        'status',
    ];

    // Constants for tax types
    public const TYPE_GST = 1;
    public const TYPE_OTHER = 2;

    // Scope for active taxes
    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    // Get tax by type
    public static function getByType($type)
    {
        return self::active()->where('type', $type)->first();
    }
}
