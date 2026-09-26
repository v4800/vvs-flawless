VVS FLAWLESS — NOUVELLE RÉSERVATION

Référence : {{ $reservation->reservation_number }}
Montre : {{ $reservation->watch->name }}
Mouvement : {{ $reservation->movement }}
Prix : {{ number_format($reservation->price, 0, ',', ' ') }} €
Acompte : {{ number_format(\App\Support\ReservationPayment::depositAmount($reservation->price, $reservation->deposit_amount_snapshot), 0, ',', ' ') }} €
Solde : {{ number_format(\App\Support\ReservationPayment::balanceAmount($reservation->price, $reservation->deposit_amount_snapshot), 0, ',', ' ') }} €
Statut : {{ $reservation->status }}

CLIENT
Nom : {{ $reservation->customer_name }}
E-mail : {{ $reservation->email }}
Téléphone : {{ $reservation->phone }}
Ville : {{ $reservation->city ?: 'Non renseignée' }}
Mode de réception : {{ $reservation->delivery_method }}

@if ($reservation->message)
MESSAGE
{{ $reservation->message }}
@endif

Tableau de bord :
{{ route('dashboard') }}
