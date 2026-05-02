<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComboDevice extends Model
{
    //
    protected $fillable = [
        'title',
        'description',
        'price',
        'condition',
        'images',
        'is_active',
    ];

    protected $casts = [
        'images' => 'array',
        'is_active' => 'boolean',
        'price' => 'decimal:2',
    ];
}
