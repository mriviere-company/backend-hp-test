<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductionSpeed extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_type',
        'units_per_hour',
    ];

    protected $casts = [
        'product_type' => 'integer',
        'units_per_hour' => 'integer',
    ];
}
