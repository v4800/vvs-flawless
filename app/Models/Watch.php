<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property string $name
 * @property string $slug
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

    protected static function booted(): void
    {
        static::creating(function (Watch $watch): void {
            if (is_string($watch->slug) && trim($watch->slug) !== '') {
                return;
            }

            $baseSlug = Str::slug($watch->name);

            if ($baseSlug === '') {
                $baseSlug = 'watch';
            }

            $slug = $baseSlug;
            $suffix = 2;

            while (static::query()->where('slug', $slug)->exists()) {
                $slug = $baseSlug.'-'.$suffix;
                $suffix++;
            }

            $watch->slug = $slug;
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
