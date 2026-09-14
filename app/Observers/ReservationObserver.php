<?php

namespace App\Observers;

use App\Models\Reservation;
use App\Models\Watch;

class ReservationObserver
{
    public function creating(Reservation $reservation): void
    {
        if (
            $reservation->watch_name_snapshot !== null
            && $reservation->watch_image_snapshot !== null
        ) {
            return;
        }

        $watch = Watch::query()->find($reservation->watch_id);

        if ($watch === null) {
            return;
        }

        $reservation->watch_name_snapshot ??= $watch->name;
        $reservation->watch_image_snapshot ??= $watch->image;
    }

    public function updated(Reservation $reservation): void
    {
        if (! $reservation->wasChanged('status')) {
            return;
        }

        $reservation->statusHistories()->create([
            'changed_by' => auth()->id(),
            'old_status' => $reservation->getOriginal('status'),
            'new_status' => $reservation->status,
            'created_at' => now(),
        ]);
    }
}
