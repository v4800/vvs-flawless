<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Reservation extends Model
{
    protected $fillable = [
        'watch_id',
        'watch_name_snapshot',
        'watch_image_snapshot',
        'movement',
        'price',
        'deposit_amount',
        'customer_name',
        'email',
        'phone',
        'city',
        'delivery_method',
        'locale',
        'status',
        'reservation_number',
        'message',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_term',
        'utm_content',
        'referrer',
        'landing_page',
        'deposit_paid_at',
        'balance_paid_at',
        'appointment_at',
        'handover_address',
        'travel_fee',
        'admin_notes',
        'is_test',
        'archived_at',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'deposit_amount' => 'decimal:2',
        'travel_fee' => 'decimal:2',
        'deposit_paid_at' => 'datetime',
        'balance_paid_at' => 'datetime',
        'appointment_at' => 'datetime',
        'is_test' => 'boolean',
        'archived_at' => 'datetime',
    ];

    /**
     * @return BelongsTo<Watch, $this>
     */
    public function watch(): BelongsTo
    {
        return $this->belongsTo(Watch::class);
    }

    /**
     * @return HasMany<ReservationStatusHistory, $this>
     */
    public function statusHistories(): HasMany
    {
        return $this->hasMany(ReservationStatusHistory::class)
            ->latest('created_at');
    }
}
