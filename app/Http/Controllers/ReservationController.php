<?php

namespace App\Http\Controllers;

use App\Mail\CustomerReservationMail;
use App\Mail\NewReservationMail;
use App\Models\Reservation;
use App\Models\Watch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Symfony\Component\HttpFoundation\Response as SymfonyResponse;

class ReservationController extends Controller
{
    public function store(Request $request): SymfonyResponse
    {
        $validated = $request->validate([
            'watch_id' => [
                'required',
                'integer',
                'exists:watches,id',
            ],

            'movement' => [
                'required',
                'string',
                'in:Japonais,Suisse',
            ],

            'customer_name' => [
                'required',
                'string',
                'max:255',
            ],

            'email' => [
                'required',
                'email',
                'max:255',
            ],

            'phone' => [
                'required',
                'string',
                'max:50',
            ],

            'city' => [
                'nullable',
                'string',
                'max:255',
            ],

            'delivery_method' => [
                'required',
                'string',
                'in:Remise en main propre,Livraison',
            ],

            'message' => [
                'nullable',
                'string',
                'max:2000',
            ],

            'confirmation' => [
                'required',
                'accepted',
            ],
        ]);

        $watch = Watch::query()->findOrFail(
            (int) $validated['watch_id']
        );

        if ($validated['movement'] === 'Suisse') {
            $price =
                $watch->swiss_promo_price
                ?? $watch->swiss_price;
        } else {
            $price =
                $watch->japanese_promo_price
                ?? $watch->japanese_price;
        }

        abort_if(
            $price === null,
            422,
            'Prix indisponible.'
        );

        do {
            $reservationNumber =
                'VVS-'.strtoupper(Str::random(12));
        } while (
            Reservation::query()
                ->where(
                    'reservation_number',
                    $reservationNumber
                )
                ->exists()
        );

        $attribution = $request
            ->session()
            ->get(
                'marketing_attribution',
                []
            );

        if (! is_array($attribution)) {
            $attribution = [];
        }

        $reservation = Reservation::create([
            'watch_id' => $watch->id,

            'movement' => $validated['movement'],

            'price' => $price,

            'customer_name' => $validated['customer_name'],

            'email' => $validated['email'],

            'phone' => $validated['phone'],

            'city' => $validated['city'] ?? null,

            'delivery_method' => $validated['delivery_method'],

            'status' => 'Nouvelle demande',

            'reservation_number' => $reservationNumber,

            'message' => $validated['message'] ?? null,

            'utm_source' => $this->attributionValue(
                $attribution,
                'utm_source'
            ),

            'utm_medium' => $this->attributionValue(
                $attribution,
                'utm_medium'
            ),

            'utm_campaign' => $this->attributionValue(
                $attribution,
                'utm_campaign'
            ),

            'utm_term' => $this->attributionValue(
                $attribution,
                'utm_term'
            ),

            'utm_content' => $this->attributionValue(
                $attribution,
                'utm_content'
            ),

            'referrer' => $this->attributionValue(
                $attribution,
                'referrer'
            ),

            'landing_page' => $this->attributionValue(
                $attribution,
                'landing_page'
            ),
        ]);

        $reservation->load('watch');

        $confirmationRoute = app()->getLocale() === 'nl_BE'
            ? 'nl.reservations.confirmation'
            : 'reservations.confirmation';

        $confirmationUrl =
            URL::temporarySignedRoute(
                $confirmationRoute,
                now()->addHours(24),
                [
                    'reservationNumber' => $reservation
                        ->reservation_number,
                ]
            );

        try {
            Mail::to(
                $reservation->email
            )->locale(
                app()->getLocale()
            )->send(
                new CustomerReservationMail(
                    $reservation,
                    $confirmationUrl
                )
            );
        } catch (\Throwable $exception) {
            Log::error(
                'Erreur mail client VVS FLAWLESS',
                [
                    'reservation_id' => $reservation->id,
                    'error' => $exception->getMessage(),
                ]
            );
        }

        $adminEmail = config(
            'vvs.admin_email'
        );

        if (
            is_string($adminEmail)
            && $adminEmail !== ''
        ) {
            try {
                Mail::to(
                    $adminEmail
                )->send(
                    new NewReservationMail(
                        $reservation
                    )
                );
            } catch (\Throwable $exception) {
                Log::error(
                    'Erreur mail admin VVS FLAWLESS',
                    [
                        'reservation_id' => $reservation->id,
                        'error' => $exception->getMessage(),
                    ]
                );
            }
        }

        return Inertia::location(
            $confirmationUrl
        );
    }

    public function confirmation(
        string $reservationNumber
    ): SymfonyResponse {
        $reservation =
            Reservation::query()
                ->with('watch')
                ->where(
                    'reservation_number',
                    $reservationNumber
                )
                ->firstOrFail();

        $watch = $reservation->watch;

        abort_if(
            $watch === null,
            404
        );

        $watchName = $watch->name;

        if (app()->getLocale() === 'nl_BE') {
            $translatedWatch = trans('watches.'.$watch->id);

            if (is_array($translatedWatch)
                && is_string($translatedWatch['name'] ?? null)) {
                $watchName = $translatedWatch['name'];
            }
        }

        $dateFormat = app()->getLocale() === 'nl_BE'
            ? 'd/m/Y \\o\\m H:i'
            : 'd/m/Y à H:i';

        $response =
            Inertia::render(
                'Reservations/Confirmation',
                [
                    'reservation' => [
                        'reservation_number' => $reservation
                            ->reservation_number,

                        'customer_name' => $reservation
                            ->customer_name,

                        'email' => $reservation
                            ->email,

                        'phone' => $reservation
                            ->phone,

                        'city' => $reservation
                            ->city,

                        'movement' => $reservation
                            ->movement,

                        'price' => (float) $reservation
                            ->price,

                        'delivery_method' => $reservation
                            ->delivery_method,

                        'status' => $reservation
                            ->status,

                        'message' => $reservation
                            ->message,

                        'date' => $reservation
                            ->created_at
                            ->copy()
                            ->timezone(
                                'Europe/Brussels'
                            )
                            ->format(
                                $dateFormat
                            ),
                    ],

                    'watch' => [
                        'id' => $watch->id,
                        'name' => $watchName,
                        'image' => $watch->image,
                    ],
                ]
            )->toResponse(
                request()
            );

        $response->headers->set(
            'Cache-Control',
            'no-store, private, max-age=0, must-revalidate'
        );

        $response->headers->set(
            'Pragma',
            'no-cache'
        );

        $response->headers->set(
            'Expires',
            '0'
        );

        return $response;
    }

    /**
     * @param  array<mixed>  $attribution
     */
    private function attributionValue(
        array $attribution,
        string $key
    ): ?string {
        $value = $attribution[$key] ?? null;

        if (! is_string($value)) {
            return null;
        }

        $value = trim($value);

        return $value !== ''
            ? $value
            : null;
    }
}
