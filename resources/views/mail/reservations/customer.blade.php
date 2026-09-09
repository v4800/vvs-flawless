@php
    $watchName = $reservation->watch->name;

    $translatedWatch = trans('watches.'.$reservation->watch->id);

    if (is_array($translatedWatch) && is_string($translatedWatch['name'] ?? null)) {
        $watchName = $translatedWatch['name'];
    }

    $movement = $reservation->movement === 'Suisse'
        ? trans('site.movements.suisse')
        : trans('site.movements.japonais');

    $deliveryMethod = $reservation->delivery_method === 'Livraison'
        ? trans('site.product.delivery')
        : trans('site.product.handover');
@endphp

<x-mail::message>

# {{ trans('site.mail.title') }}

{{ trans('site.mail.hello', ['name' => $reservation->customer_name]) }}

{{ trans('site.mail.recorded') }}

## {{ trans('site.mail.summary') }}

**{{ trans('site.mail.number') }} :**
{{ $reservation->reservation_number }}

**{{ trans('site.mail.watch') }} :**
{{ $watchName }}

**{{ trans('site.mail.movement') }} :**
{{ $movement }}

**{{ trans('site.mail.reserved_price') }} :**
{{ number_format($reservation->price, 0, ',', ' ') }} €

**{{ trans('site.mail.name') }} :**
{{ $reservation->customer_name }}

**{{ trans('site.mail.email') }} :**
{{ $reservation->email }}

**{{ trans('site.mail.phone') }} :**
{{ $reservation->phone }}

**{{ trans('site.mail.city') }} :**
{{ $reservation->city ?: trans('site.mail.not_provided') }}

**{{ trans('site.mail.reception_method') }} :**

{{ $deliveryMethod }}

**{{ trans('site.mail.status') }} :**
{{ trans('site.confirmation.new_request') }}

@if ($reservation->message)

**{{ trans('site.mail.your_message') }} :**

{{ $reservation->message }}

@endif

<x-mail::button :url="$confirmationUrl">
{{ trans('site.mail.button') }}
</x-mail::button>

{{ trans('site.mail.next') }}

{{ trans('site.mail.legal_note') }}

{{ trans('site.mail.thanks') }}

**VVS FLAWLESS**
{{ trans('site.footer.tagline') }}

</x-mail::message>
