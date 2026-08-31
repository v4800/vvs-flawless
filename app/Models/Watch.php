<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Watch extends Model
{
    protected $fillable = [
        'name',
        'price',
        'promo_price',
        'japanese_price',
        'japanese_promo_price',
        'swiss_price',
        'swiss_promo_price',
        'description',
        'availability',
        'stock_quantity',
        'image',
    ];
}