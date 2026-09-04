<x-mail::message>

# Nouvelle réservation VVS FLAWLESS

Une nouvelle réservation vient d'être enregistrée.

## Commande

**Numéro :**  
{{ $reservation->reservation_number }}

**Montre :**  
{{ $reservation->watch->name }}

**Mouvement :**  
{{ $reservation->movement }}

**Prix :**  
{{ number_format($reservation->price, 0, ',', ' ') }} €

**Statut :**  
{{ $reservation->status }}

## Client

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

@if ($reservation->message)

## Message du client

{{ $reservation->message }}

@endif

<x-mail::button :url="route('dashboard')">
Voir dans le dashboard
</x-mail::button>

**VVS FLAWLESS**

</x-mail::message>
