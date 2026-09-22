<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SendReservationEmailRequest;
use App\Http\Requests\Admin\UpdateReservationRequest;
use App\Mail\AdminReservationContactMail;
use App\Mail\ReservationRecapMail;
use App\Models\Reservation;
use App\Models\Watch;
use App\Support\ReservationPayment;
use App\Support\ReservationRecapPdf;
use App\Support\ReservationWorkflow;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Throwable;
use Inertia\Response;

class ReservationDashboardController extends Controller
{
    public function index(Request $request): Response
    {
        abort_unless((bool) $request->user()?->is_admin, 403);

        $filters = $request->validate([
            'q' => ['nullable', 'string', 'max:120'],
            'status' => [
                'nullable',
                'string',
                Rule::in(ReservationWorkflow::acceptedStatuses()),
            ],
            'date_from' => ['nullable', 'date'],
            'date_to' => ['nullable', 'date', 'after_or_equal:date_from'],
            'watch_id' => ['nullable', 'integer', 'exists:watches,id'],
            'city' => ['nullable', 'string', 'max:255'],
            'source' => ['nullable', 'string', 'max:255'],
            'show_archived' => ['nullable', 'boolean'],
            'show_test' => ['nullable', 'boolean'],
            'sort' => ['nullable', Rule::in(['latest', 'oldest', 'appointment'])],
        ]);

        $showArchived = $request->boolean('show_archived');
        $showTest = $request->boolean('show_test');

        $query = Reservation::query()
            ->with([
                'watch:id,name,image',
                'statusHistories.user:id,name',
            ])
            ->when(
                ! $showArchived,
                fn (Builder $builder) => $builder->whereNull('archived_at')
            )
            ->when(
                ! $showTest,
                fn (Builder $builder) => $builder->where('is_test', false)
            );

        $this->applyFilters($query, $filters);

        match ($filters['sort'] ?? 'latest') {
            'oldest' => $query->oldest(),
            'appointment' => $query
                ->orderByRaw('appointment_at IS NULL')
                ->orderBy('appointment_at')
                ->latest('id'),
            default => $query->latest(),
        };

        $reservations = $query
            ->paginate(20)
            ->withQueryString()
            ->through(fn (Reservation $reservation) => $this->serializeReservation($reservation));

        return inertia('Admin/Reservations/Index', [
            'reservations' => $reservations,
            'stats' => $this->statistics(),
            'filters' => [
                'q' => $filters['q'] ?? '',
                'status' => $filters['status'] ?? '',
                'date_from' => $filters['date_from'] ?? '',
                'date_to' => $filters['date_to'] ?? '',
                'watch_id' => isset($filters['watch_id'])
                    ? (string) $filters['watch_id']
                    : '',
                'city' => $filters['city'] ?? '',
                'source' => $filters['source'] ?? '',
                'show_archived' => $showArchived,
                'show_test' => $showTest,
                'sort' => $filters['sort'] ?? 'latest',
            ],
            'filterOptions' => [
                'statuses' => ReservationWorkflow::acceptedStatuses(),
                'cities' => Reservation::query()
                    ->whereNotNull('city')
                    ->where('city', '!=', '')
                    ->distinct()
                    ->orderBy('city')
                    ->pluck('city')
                    ->values(),
                'sources' => Reservation::query()
                    ->whereNotNull('utm_source')
                    ->where('utm_source', '!=', '')
                    ->distinct()
                    ->orderBy('utm_source')
                    ->pluck('utm_source')
                    ->values(),
                'watches' => Watch::query()
                    ->select(['id', 'name'])
                    ->orderBy('name')
                    ->get(),
            ],
            'workflowStatuses' => ReservationWorkflow::statuses(),
        ]);
    }

    public function update(
        UpdateReservationRequest $request,
        Reservation $reservation
    ): RedirectResponse {
        $reservation->fill($request->validated());
        $reservation->save();

        return back()->with('success', 'Réservation mise à jour.');
    }

    public function sendEmail(
        SendReservationEmailRequest $request,
        Reservation $reservation
    ): RedirectResponse {
        $validated = $request->validated();

        try {
            Mail::to($reservation->email)->send(
                new AdminReservationContactMail(
                    $reservation,
                    $validated['subject'],
                    $validated['message']
                )
            );
        } catch (Throwable) {
            return back()->withErrors([
                'email_message' => 'L’email n’a pas pu être envoyé. Vérifie la configuration mail du site.',
            ]);
        }

        return back()->with('success', 'Email envoyé au client.');
    }

    public function downloadRecap(
        Request $request,
        Reservation $reservation,
        ReservationRecapPdf $pdf
    ): HttpResponse {
        abort_unless((bool) $request->user()?->is_admin, 403);

        return response(
            $pdf->make($reservation),
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'attachment; filename="'.$pdf->filename($reservation).'"',
                'Cache-Control' => 'no-store, private, max-age=0, must-revalidate',
                'X-Robots-Tag' => 'noindex, nofollow, noarchive',
            ]
        );
    }

    public function sendRecap(
        Request $request,
        Reservation $reservation
    ): RedirectResponse {
        abort_unless((bool) $request->user()?->is_admin, 403);

        try {
            Mail::to($reservation->email)->send(
                new ReservationRecapMail($reservation)
            );
        } catch (Throwable) {
            return back()->withErrors([
                'email_message' => 'Le récapitulatif n’a pas pu être envoyé. Vérifie la configuration mail du site.',
            ]);
        }

        return back()->with('success', 'Récapitulatif PDF envoyé au client.');
    }

    public function archive(
        Request $request,
        Reservation $reservation
    ): RedirectResponse {
        abort_unless((bool) $request->user()?->is_admin, 403);

        $validated = $request->validate([
            'archived' => ['required', 'boolean'],
        ]);

        $reservation->update([
            'archived_at' => $validated['archived'] ? now() : null,
        ]);

        return back()->with(
            'success',
            $validated['archived']
                ? 'Réservation archivée.'
                : 'Réservation restaurée.'
        );
    }

    /**
     * @param  array<string, mixed>  $filters
     */
    private function applyFilters(Builder $query, array $filters): void
    {
        $search = trim((string) ($filters['q'] ?? ''));

        if ($search !== '') {
            $query->where(function (Builder $builder) use ($search): void {
                $like = '%'.$search.'%';

                $builder
                    ->where('customer_name', 'like', $like)
                    ->orWhere('email', 'like', $like)
                    ->orWhere('phone', 'like', $like)
                    ->orWhere('reservation_number', 'like', $like)
                    ->orWhere('city', 'like', $like)
                    ->orWhere('watch_name_snapshot', 'like', $like)
                    ->orWhereHas(
                        'watch',
                        fn (Builder $watchQuery) => $watchQuery
                            ->where('name', 'like', $like)
                    );
            });
        }

        $query
            ->when(
                $filters['status'] ?? null,
                fn (Builder $builder, string $status) => $builder
                    ->where('status', $status)
            )
            ->when(
                $filters['date_from'] ?? null,
                fn (Builder $builder, string $date) => $builder
                    ->whereDate('created_at', '>=', $date)
            )
            ->when(
                $filters['date_to'] ?? null,
                fn (Builder $builder, string $date) => $builder
                    ->whereDate('created_at', '<=', $date)
            )
            ->when(
                $filters['watch_id'] ?? null,
                fn (Builder $builder, int $watchId) => $builder
                    ->where('watch_id', $watchId)
            )
            ->when(
                $filters['city'] ?? null,
                fn (Builder $builder, string $city) => $builder
                    ->where('city', $city)
            )
            ->when(
                $filters['source'] ?? null,
                fn (Builder $builder, string $source) => $builder
                    ->where('utm_source', $source)
            );
    }

    /**
     * @return array<string, int>
     */
    private function statistics(): array
    {
        $counts = Reservation::query()
            ->whereNull('archived_at')
            ->where('is_test', false)
            ->selectRaw('status, COUNT(*) as aggregate')
            ->groupBy('status')
            ->pluck('aggregate', 'status');

        $statistics = [];

        foreach (ReservationWorkflow::statisticGroups() as $key => $statuses) {
            $statistics[$key] = array_sum(array_map(
                fn (string $status): int => (int) ($counts[$status] ?? 0),
                $statuses
            ));
        }

        return $statistics;
    }

    /**
     * @return array<string, mixed>
     */
    private function serializeReservation(Reservation $reservation): array
    {
        $price = $reservation->price !== null
            ? (float) $reservation->price
            : null;

        $depositAmount = ReservationPayment::depositAmount($price);
        $balanceAmount = ReservationPayment::balanceAmount($price);

        $confirmedPaidAmount = ReservationPayment::confirmedPaidAmount(
            $price,
            $reservation->deposit_paid_at !== null,
            $reservation->balance_paid_at !== null
        );

        return [
            'id' => $reservation->id,
            'reservation_number' => $reservation->reservation_number,
            'customer_name' => $reservation->customer_name,
            'email' => $reservation->email,
            'phone' => $reservation->phone,
            'city' => $reservation->city,
            'delivery_method' => $reservation->delivery_method,
            'message' => $reservation->message,
            'movement' => $reservation->movement,
            'price' => $price,
            'deposit_amount' => $depositAmount,
            'balance_amount' => $balanceAmount,
            'confirmed_paid_amount' => $confirmedPaidAmount,
            'status' => $reservation->status,
            'watch_id' => $reservation->watch_id,
            'watch_name_snapshot' => $reservation->watch_name_snapshot,
            'watch_image_snapshot' => $reservation->watch_image_snapshot,
            'watch' => $reservation->watch === null
                ? null
                : [
                    'id' => $reservation->watch->id,
                    'name' => $reservation->watch->name,
                    'image' => $reservation->watch->image,
                ],
            'deposit_paid_at' => $reservation->deposit_paid_at?->toIso8601String(),
            'balance_paid_at' => $reservation->balance_paid_at?->toIso8601String(),
            'appointment_at' => $reservation->appointment_at?->toIso8601String(),
            'handover_address' => $reservation->handover_address,
            'travel_fee' => (float) $reservation->travel_fee,
            'admin_notes' => $reservation->admin_notes,
            'is_test' => $reservation->is_test,
            'archived_at' => $reservation->archived_at?->toIso8601String(),
            'utm_source' => $reservation->utm_source,
            'utm_medium' => $reservation->utm_medium,
            'utm_campaign' => $reservation->utm_campaign,
            'utm_term' => $reservation->utm_term,
            'utm_content' => $reservation->utm_content,
            'referrer' => $reservation->referrer,
            'landing_page' => $reservation->landing_page,
            'created_at' => $reservation->created_at?->toIso8601String(),
            'review_invitation_allowed' => ReservationWorkflow::isCompleted(
                $reservation->status
            ),
            'status_histories' => $reservation->statusHistories
                ->map(fn ($history) => [
                    'id' => $history->id,
                    'old_status' => $history->old_status,
                    'new_status' => $history->new_status,
                    'changed_by' => $history->user?->name,
                    'created_at' => $history->created_at?->toIso8601String(),
                ])
                ->values(),
        ];
    }
}
