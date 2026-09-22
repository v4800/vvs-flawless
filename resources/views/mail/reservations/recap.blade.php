@php
    $price = $reservation->price !== null ? (float) $reservation->price : 0;
    $deposit = \App\Support\ReservationPayment::depositAmount($price) ?? 0;
    $balance = \App\Support\ReservationPayment::balanceAmount($price) ?? 0;
@endphp
<x-mail::message>
# VVS FLAWLESS

{{ trans('site.mail.hello', ['name' => $reservation->customer_name]) }}

{{ trans('site.mail.summary') }} **{{ $reservation->reservation_number }}**

**{{ trans('site.mail.reserved_price') }} :** {{ number_format($price, 2, ',', ' ') }} €  
**{{ trans('site.mail.deposit') }} :** {{ number_format($deposit, 2, ',', ' ') }} €  
**{{ trans('site.mail.balance') }} :** {{ number_format($balance, 2, ',', ' ') }} €

{{ trans('site.mail.next') }}

{{ trans('site.mail.legal_note') }}

{{ trans('site.mail.document_note') }}

{{ trans('site.mail.thanks') }}<br>
**VVS FLAWLESS**
</x-mail::message>
