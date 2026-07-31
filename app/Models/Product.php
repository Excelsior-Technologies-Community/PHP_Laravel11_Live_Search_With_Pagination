<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'details',
        'image',
        'size',
        'color',
        'category',
        'price',
        'status',
        'stock',
        'low_stock_threshold',
    ];

    protected $casts = [
        'stock' => 'integer',
        'low_stock_threshold' => 'integer',
    ];
}
