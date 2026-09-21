@php
    $localizedWatch = app(\App\Support\WatchCatalog::class)
        ->localizedWatch($reservation->watch);

    $watchName = $localizedWatch->name;

    $movement = match ($reservation->movement) {
        'Modele presente' => $localizedWatch->getAttribute('presented_copy')['model_label'],
        'Suisse' => trans('site.movements.suisse'),
        default => trans('site.movements.japonais'),
    };

    $deliveryMethod = $reservation->delivery_method === 'Livraison'
        ? trans('site.product.delivery')
        : trans('site.product.handover');
@endphp
VVS FLAWLESS

{{ trans('site.mail.hello', ['name' => $reservation->customer_name]) }}

{{ trans('site.mail.recorded') }}

{{ trans('site.mail.summary') }}
{{ trans('site.mail.number') }} : {{ $reservation->reservation_number }}
{{ trans('site.mail.watch') }} : {{ $watchName }}
{{ trans('site.mail.movement') }} : {{ $movement }}
{{ trans('site.mail.reserved_price') }} : {{ number_format($reservation->price, 0, ',', ' ') }} €
{{ trans('site.mail.reception_method') }} : {{ $deliveryMethod }}

@if ($reservation->message)
{{ trans('site.mail.your_message') }} :
{{ $reservation->message }}
@endif

{{ trans('site.mail.next') }}

{{ trans('site.mail.button') }} :
{{ $confirmationUrl }}

{{ trans('site.mail.legal_note') }}

VVS FLAWLESS
{{ trans('site.footer.tagline') }}
