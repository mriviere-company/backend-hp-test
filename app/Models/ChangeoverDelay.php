<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChangeoverDelay extends Model
{
    use HasFactory;

    protected $fillable = [
        'delay_minutes',
    ];

    protected $casts = [
        'delay_minutes' => 'integer',
    ];
}
