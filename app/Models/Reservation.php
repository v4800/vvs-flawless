<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Reservation extends Model
{
    protected $fillable = [
        'watch_id',
        'movement',
        'price',
        'customer_name',
        'email',
        'phone',
        'city',
        'delivery_method',
        'status',
        'reservation_number',
        'message',
        'utm_source',
        'utm_medium',
        'utm_campaign',
    ];

    protected $casts = [
        'price' => 'decimal:2',
    ];

    /**
     * @return BelongsTo<Watch, $this>
     */
    public function watch(): BelongsTo
    {
        return $this->belongsTo(Watch::class);
    }
}
