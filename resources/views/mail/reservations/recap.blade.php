@php
    $price = $reservation->price !== null ? (float) $reservation->price : 0;
    $deposit = \App\Support\ReservationPayment::depositAmount($price) ?? 0;
    $balance = \App\Support\ReservationPayment::balanceAmount($price) ?? 0;
@endphp
<x-mail::message>
# VVS FLAWLESS

Bonjour {{ $reservation->customer_name }},

Vous trouverez en pièce jointe le **récapitulatif de votre réservation {{ $reservation->reservation_number }}**.

**Prix total :** {{ number_format($price, 2, ',', ' ') }} €  
**Acompte fixe :** {{ number_format($deposit, 2, ',', ' ') }} €  
**Solde restant :** {{ number_format($balance, 2, ',', ' ') }} €

Aucun paiement n’est effectué automatiquement sur le site. La préparation débute après validation des détails et confirmation de l’acompte.

Lorsque la montre est prête, nous vous transmettons les éléments convenus et organisons le règlement du solde ainsi que la remise ou la livraison.

Merci,<br>
**VVS FLAWLESS**
</x-mail::message>
