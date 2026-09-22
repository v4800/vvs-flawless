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

    $watchImageUrl = url(
        app(\App\Support\WatchCatalog::class)
            ->applyCover($reservation->watch)
            ->getAttribute('image')
    );
@endphp
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <meta name="color-scheme" content="dark">
    <meta name="supported-color-schemes" content="dark">
    <title>{{ trans('site.mail.title') }}</title>
    <style>
        @media only screen and (max-width: 640px) {
            .vvs-container { width: 100% !important; }
            .vvs-padding { padding-left: 22px !important; padding-right: 22px !important; }
            .vvs-title { font-size: 30px !important; line-height: 36px !important; }
            .vvs-detail { display: block !important; width: 100% !important; }
            .vvs-detail + .vvs-detail { padding-top: 18px !important; }
            .vvs-button { display: block !important; text-align: center !important; }
        }
    </style>
</head>
<body bgcolor="#030303" style="margin:0;padding:0;background:#030303;color:#f5f1e8;font-family:Arial,Helvetica,sans-serif;">
    <div style="display:none;max-height:0;overflow:hidden;opacity:0;">
        {{ trans('site.mail.recorded') }} — {{ $reservation->reservation_number }}
    </div>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background:#030303;">
        <tr>
            <td align="center" style="padding:32px 12px;">
                <table class="vvs-container" role="presentation" width="640" cellspacing="0" cellpadding="0" border="0" style="width:640px;max-width:640px;border:1px solid #40382c;border-radius:22px;background:#0b0b0a;overflow:hidden;">
                    <tr>
                        <td class="vvs-padding" style="padding:25px 34px;border-bottom:1px solid #292722;background:#0d0d0c;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                <tr>
                                    <td>
                                        <div style="font-size:18px;font-weight:800;letter-spacing:2px;color:#f5f1e8;">
                                            VVS FLAWLESS
                                        </div>
                                        <div style="margin-top:5px;font-size:11px;letter-spacing:1.4px;color:#ad9a78;">
                                            {{ trans('site.mail.brand_line') }}
                                        </div>
                                    </td>
                                    <td align="right" style="font-size:11px;color:#8e8980;">
                                        {{ $reservation->reservation_number }}
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td class="vvs-padding" style="padding:42px 34px 28px;">
                            <div style="display:inline-block;padding:7px 12px;border:1px solid #8f7b59;border-radius:999px;background:#1b1915;font-size:10px;font-weight:700;letter-spacing:1.7px;color:#d7c29d;">
                                {{ trans('site.mail.recorded_badge') }}
                            </div>

                            <h1 class="vvs-title" style="margin:20px 0 12px;font-family:Georgia,'Times New Roman',serif;font-size:38px;line-height:44px;font-weight:500;color:#f7f3ea;">
                                {{ trans('site.mail.title') }}
                            </h1>

                            <p style="margin:0 0 10px;font-size:15px;line-height:25px;color:#d8d4cc;">
                                {{ trans('site.mail.hello', ['name' => $reservation->customer_name]) }}
                            </p>

                            <p style="margin:0;font-size:14px;line-height:24px;color:#aaa59c;">
                                {{ trans('site.mail.recorded') }}
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td class="vvs-padding" style="padding:0 34px 30px;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="border:1px solid #302d27;border-radius:16px;background:#0c0c0c;">
                                <tr>
                                    <td bgcolor="#030303" style="padding:12px;border-bottom:1px solid #302d27;background:#030303;">
                                        <a href="{{ $confirmationUrl }}" style="display:block;text-decoration:none;">
                                            <img
                                                class="vvs-email-watch-image"
                                                src="{{ $watchImageUrl }}"
                                                alt="{{ $watchName }}"
                                                width="566"
                                                style="display:block;width:100%;max-width:566px;max-height:420px;margin:0 auto;border:0;border-radius:11px;background:#030303;object-fit:contain;"
                                            >
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:22px;border-bottom:1px solid #292722;">
                                        <div style="font-size:10px;font-weight:700;letter-spacing:1.8px;color:#a99470;">
                                            {{ strtoupper(trans('site.mail.summary')) }}
                                        </div>
                                        <div style="margin-top:9px;font-family:Georgia,'Times New Roman',serif;font-size:23px;line-height:30px;color:#f4efe5;">
                                            {{ $watchName }}
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding:22px;">
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                            <tr>
                                                <td class="vvs-detail" width="50%" valign="top" style="padding-right:12px;">
                                                    <div style="font-size:10px;letter-spacing:1.2px;color:#77736c;">
                                                        {{ strtoupper(trans('site.mail.movement')) }}
                                                    </div>
                                                    <div style="margin-top:7px;font-size:14px;font-weight:700;color:#eee9df;">
                                                        {{ $movement }}
                                                    </div>
                                                </td>

                                                <td class="vvs-detail" width="50%" valign="top">
                                                    <div style="font-size:10px;letter-spacing:1.2px;color:#77736c;">
                                                        {{ strtoupper(trans('site.mail.reserved_price')) }}
                                                    </div>
                                                    <div style="margin-top:7px;font-size:25px;font-weight:800;color:#d8c49e;">
                                                        {{ $formatMoney((float) $reservation->price) }}
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td colspan="2" style="height:22px;"></td>
                                            </tr>

                                            <tr>
                                                <td class="vvs-detail" width="50%" valign="top" style="padding-right:12px;">
                                                    <div style="font-size:10px;letter-spacing:1.2px;color:#77736c;">
                                                        {{ strtoupper(trans('site.mail.deposit')) }}
                                                    </div>
                                                    <div style="margin-top:7px;font-size:14px;font-weight:700;color:#d8c49e;">
                                                        {{ $formatMoney((float) $deposit) }}
                                                    </div>
                                                </td>

                                                <td class="vvs-detail" width="50%" valign="top">
                                                    <div style="font-size:10px;letter-spacing:1.2px;color:#77736c;">
                                                        {{ strtoupper(trans('site.mail.balance')) }}
                                                    </div>
                                                    <div style="margin-top:7px;font-size:14px;font-weight:700;color:#eee9df;">
                                                        {{ $formatMoney((float) $balance) }}
                                                    </div>
                                                </td>
                                            </tr>

                                            <tr>
                                                <td colspan="2" style="height:22px;"></td>
                                            </tr>

                                            <tr>
                                                <td class="vvs-detail" width="50%" valign="top" style="padding-right:12px;">
                                                    <div style="font-size:10px;letter-spacing:1.2px;color:#77736c;">
                                                        {{ strtoupper(trans('site.mail.reception_method')) }}
                                                    </div>
                                                    <div style="margin-top:7px;font-size:14px;color:#eee9df;">
                                                        {{ $deliveryMethod }}
                                                    </div>
                                                </td>

                                                <td class="vvs-detail" width="50%" valign="top">
                                                    <div style="font-size:10px;letter-spacing:1.2px;color:#77736c;">
                                                        {{ strtoupper(trans('site.mail.number')) }}
                                                    </div>
                                                    <div style="margin-top:7px;font-size:14px;color:#eee9df;">
                                                        {{ $reservation->reservation_number }}
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    @if ($reservation->message)
                        <tr>
                            <td class="vvs-padding" style="padding:0 34px 28px;">
                                <div style="padding:18px;border-left:2px solid #ad9873;background:#171614;">
                                    <div style="font-size:10px;letter-spacing:1.3px;color:#8e877d;">
                                        {{ strtoupper(trans('site.mail.your_message')) }}
                                    </div>
                                    <div style="margin-top:9px;font-size:14px;line-height:23px;color:#cbc6bc;">
                                        {!! nl2br(e($reservation->message)) !!}
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endif

                    <tr>
                        <td class="vvs-padding" style="padding:0 34px 34px;">
                            <a class="vvs-button" href="{{ $confirmationUrl }}" style="display:inline-block;padding:15px 23px;border:1px solid #d9c7a5;border-radius:11px;background:#c6ae84;color:#171512;font-size:12px;font-weight:800;letter-spacing:1px;text-decoration:none;">
                                {{ trans('site.mail.button') }}
                            </a>

                            <p style="margin:25px 0 0;font-size:14px;line-height:24px;color:#b4afa6;">
                                {{ trans('site.mail.next') }}
                            </p>

                            <p style="margin:12px 0 0;font-size:11px;line-height:19px;color:#706c65;">
                                {{ trans('site.mail.legal_note') }}
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td class="vvs-padding" style="padding:23px 34px;border-top:1px solid #292722;background:#0b0b0a;">
                            <div style="font-size:12px;font-weight:800;letter-spacing:1.4px;color:#d8d2c7;">
                                VVS FLAWLESS
                            </div>
                            <div style="margin-top:6px;font-size:10px;line-height:17px;color:#69655f;">
                                {{ trans('site.footer.tagline') }}
                            </div>
                        </td>
                    </tr>
                </table>

                <div style="padding:18px;font-size:10px;line-height:17px;color:#5e5a54;">
                    {{ trans('site.mail.automated_message', ['number' => $reservation->reservation_number]) }}
                </div>
            </td>
        </tr>
    </table>
</body>
</html>
