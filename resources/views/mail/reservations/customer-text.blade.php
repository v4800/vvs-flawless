@php
    $localizedWatch = app(\App\Support\WatchCatalog::class)
        ->localizedWatch($reservation->watch);

    $watchName = $localizedWatch->name;
    $presentedCopy = $localizedWatch->getAttribute('presented_copy');

    $movement = match ($reservation->movement) {
        'Modele presente' => is_array($presentedCopy)
            ? ($presentedCopy['model_label'] ?? $reservation->movement)
            : $reservation->movement,
        'Suisse' => trans('site.movements.suisse'),
        default => trans('site.movements.japonais'),
    };

    $deliveryMethod = $reservation->delivery_method === 'Livraison'
        ? trans('site.product.delivery')
        : trans('site.product.handover');

    $deposit = \App\Support\ReservationPayment::depositAmount(
        $reservation->price
    ) ?? 0;

    $balance = \App\Support\ReservationPayment::balanceAmount(
        $reservation->price
    ) ?? 0;

    $locale = app()->getLocale();

    $formatMoney = static function (float $amount) use ($locale): string {
        if ($locale === 'en_BE') {
            return '€'.number_format($amount, 2, '.', ',');
        }

        if (in_array($locale, ['nl_BE', 'de_BE'], true)) {
            return number_format($amount, 2, ',', '.').' €';
        }

        return number_format($amount, 2, ',', ' ').' €';
    };
@endphp
VVS FLAWLESS

{{ trans('site.mail.hello', ['name' => $reservation->customer_name]) }}

{{ trans('site.mail.recorded') }}

{{ trans('site.mail.summary') }}
{{ trans('site.mail.number') }} : {{ $reservation->reservation_number }}
{{ trans('site.mail.watch') }} : {{ $watchName }}
{{ trans('site.mail.movement') }} : {{ $movement }}
{{ trans('site.mail.reserved_price') }} : {{ $formatMoney((float) $reservation->price) }}
{{ trans('site.mail.deposit') }} : {{ $formatMoney((float) $deposit) }}
{{ trans('site.mail.balance') }} : {{ $formatMoney((float) $balance) }}
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
