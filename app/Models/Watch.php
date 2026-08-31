<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property string|int|float $price
 * @property string|int|float|null $promo_price
 * @property string|int|float|null $japanese_price
 * @property string|int|float|null $japanese_promo_price
 * @property string|int|float|null $swiss_price
 * @property string|int|float|null $swiss_promo_price
 * @property string|null $description
 * @property string $availability
 * @property int|null $stock_quantity
 * @property string|null $image
 */
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
