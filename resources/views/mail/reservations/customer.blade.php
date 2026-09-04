<x-mail::message>

# Votre réservation VVS FLAWLESS

Bonjour {{ $reservation->customer_name }},

Votre demande de réservation a bien été enregistrée.

## Récapitulatif

**Numéro :**  
{{ $reservation->reservation_number }}

**Montre :**  
{{ $reservation->watch->name }}

**Mouvement :**  
{{ $reservation->movement }}

**Prix réservé :**  
{{ number_format($reservation->price, 0, ',', ' ') }} €

**Nom :**  
{{ $reservation->customer_name }}

**E-mail :**  
{{ $reservation->email }}

**Téléphone :**  
{{ $reservation->phone }}

**Ville :**  
{{ $reservation->city ?: 'Non renseignée' }}

**Mode de réception :**

{{ $reservation->delivery_method }}

**Statut :**  
{{ $reservation->status }}

@if ($reservation->message)

**Votre message :**

{{ $reservation->message }}

@endif

<x-mail::button
    :url="$confirmationUrl"
>
Voir mon récapitulatif
</x-mail::button>

VVS FLAWLESS vous contactera afin de finaliser votre commande.

Ce message confirme votre demande de réservation.  
La commande devient définitive après confirmation avec VVS FLAWLESS.

Merci,

**VVS FLAWLESS**  
La culture bustdown arrive en Belgique 🇧🇪

</x-mail::message>
