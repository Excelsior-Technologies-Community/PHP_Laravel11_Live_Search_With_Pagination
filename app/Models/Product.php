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
        'image',      // SINGLE IMAGE ONLY
        'size',
        'color',
        'category',
        'price',
        'status',
    ];

    protected $dates = ['deleted_at'];
}
