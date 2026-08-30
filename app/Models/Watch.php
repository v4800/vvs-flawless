<?php

namespace App\Models;
Watch::where('name', 'AP Royal Oak Blue Iced Out')->update([
    'image' => '/images/watches/ap-royal-oak-blue.png'
]);
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
    'image',
];
}