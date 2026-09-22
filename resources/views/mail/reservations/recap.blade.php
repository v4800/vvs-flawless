@php
    $price = $reservation->price !== null ? (float) $reservation->price : 0;
    $deposit = \App\Support\ReservationPayment::depositAmount(
        $price,
        $reservation->deposit_amount
    ) ?? 0;
    $balance = \App\Support\ReservationPayment::balanceAmount(
        $price,
        $reservation->deposit_amount
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
<x-mail::message>
# VVS FLAWLESS

{{ trans('site.mail.hello', ['name' => $reservation->customer_name]) }}

{{ trans('site.mail.summary') }} **{{ $reservation->reservation_number }}**

**{{ trans('site.mail.reserved_price') }} :** {{ $formatMoney((float) $price) }}  
**{{ trans('site.mail.deposit') }} :** {{ $formatMoney((float) $deposit) }}  
**{{ trans('site.mail.balance') }} :** {{ $formatMoney((float) $balance) }}

{{ trans('site.mail.next') }}

{{ trans('site.mail.legal_note') }}

{{ trans('site.mail.document_note') }}

{{ trans('site.mail.thanks') }}<br>
**VVS FLAWLESS**
</x-mail::message>
