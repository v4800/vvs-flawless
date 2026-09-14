<x-mail::message>

# VVS FLAWLESS

Bonjour {{ $reservation->customer_name }},

<div style="white-space: pre-line">{{ $messageBody }}</div>

Référence de votre demande : **{{ $reservation->reservation_number }}**

Merci,<br>
**VVS FLAWLESS**

</x-mail::message>
