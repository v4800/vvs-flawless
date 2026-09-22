<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\ReservationStatusHistory;
use App\Support\ReservationPayment;
use App\Support\ReservationWorkflow;
use Illuminate\Database\Eloquent\Builder;
use Inertia\Response;

class AdminOverviewController extends Controller
{
    public function __invoke(): Response
    {
        $baseQuery = Reservation::query()
            ->whereNull('archived_at')
            ->where('is_test', false);

        $counts = (clone $baseQuery)
            ->selectRaw('status, COUNT(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        $groups = ReservationWorkflow::statisticGroups();

        $stat = fn (string $key): int => array_sum(array_map(
            fn (string $status): int => (int) ($counts[$status] ?? 0),
            $groups[$key]
        ));

        $confirmedRevenue = (clone $baseQuery)
            ->get(['price', 'deposit_amount', 'deposit_paid_at', 'balance_paid_at'])
            ->sum(fn (Reservation $reservation): float => ReservationPayment::confirmedPaidAmount(
                $reservation->price,
                $reservation->deposit_paid_at !== null,
                $reservation->balance_paid_at !== null,
                $reservation->deposit_amount
            ));

        $recentReservations = (clone $baseQuery)
            ->latest()
            ->limit(8)
            ->get()
            ->map(fn (Reservation $reservation): array => [
                'id' => $reservation->id,
                'reservation_number' => $reservation->reservation_number,
                'customer_name' => $reservation->customer_name,
                'watch_name' => $reservation->watch_name_snapshot
                    ?: 'Montre non renseignée',
                'price' => $reservation->price === null
                    ? null
                    : (float) $reservation->price,
                'status' => $reservation->status,
                'city' => $reservation->city,
                'created_at' => $reservation->created_at?->toIso8601String(),
            ]);

        $appointments = (clone $baseQuery)
            ->whereNotNull('appointment_at')
            ->where('appointment_at', '>=', now())
            ->orderBy('appointment_at')
            ->limit(6)
            ->get()
            ->map(fn (Reservation $reservation): array => [
                'id' => $reservation->id,
                'reservation_number' => $reservation->reservation_number,
                'customer_name' => $reservation->customer_name,
                'city' => $reservation->city,
                'appointment_at' => $reservation->appointment_at?->toIso8601String(),
                'handover_address' => $reservation->handover_address,
            ]);

        $activities = ReservationStatusHistory::query()
            ->with([
                'reservation:id,reservation_number,customer_name',
                'user:id,name',
            ])
            ->whereHas(
                'reservation',
                fn (Builder $query) => $query
                    ->whereNull('archived_at')
                    ->where('is_test', false)
            )
            ->latest('created_at')
            ->limit(6)
            ->get()
            ->map(fn (ReservationStatusHistory $history): array => [
                'id' => $history->id,
                'reservation_number' => $history->reservation?->reservation_number,
                'customer_name' => $history->reservation?->customer_name,
                'status' => $history->new_status,
                'changed_by' => $history->user?->name,
                'created_at' => $history->created_at?->toIso8601String(),
            ]);

        return inertia('Admin/Overview', [
            'stats' => [
                'new' => $stat('new'),
                'deposit_paid' => $stat('deposit_paid'),
                'appointments' => $stat('appointment'),
                'completed' => $stat('completed'),
                'confirmed_revenue' => round($confirmedRevenue, 2),
            ],
            'recentReservations' => $recentReservations,
            'appointments' => $appointments,
            'activities' => $activities,
        ]);
    }
}
