@php
    $catalogWatch = app(\App\Support\WatchCatalog::class)
        ->applyCover($reservation->watch);

    $watchImageUrl = url($catalogWatch->getAttribute('image'));
@endphp
<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width">
    <title>Nouvelle réservation VVS FLAWLESS</title>
    <style>
        @media only screen and (max-width: 640px) {
            .vvs-container { width:100% !important; }
            .vvs-padding { padding-left:22px !important; padding-right:22px !important; }
            .vvs-column { display:block !important; width:100% !important; padding:0 0 18px !important; }
            .vvs-button { display:block !important; text-align:center !important; }
        }
    </style>
</head>
<body bgcolor="#030303" style="margin:0;padding:0;background:#030303;color:#f5f1e8;font-family:Arial,Helvetica,sans-serif;">
    <div style="display:none;max-height:0;overflow:hidden;opacity:0;">
        Nouvelle demande {{ $reservation->reservation_number }} de {{ $reservation->customer_name }}
    </div>

    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background:#030303;">
        <tr>
            <td align="center" style="padding:32px 12px;">
                <table class="vvs-container" role="presentation" width="640" cellspacing="0" cellpadding="0" border="0" style="width:640px;max-width:640px;border:1px solid #40382c;border-radius:22px;background:#0b0b0a;overflow:hidden;">
                    <tr>
                        <td class="vvs-padding" style="padding:24px 34px;border-bottom:1px solid #292722;background:#0d0d0c;">
                            <div style="font-size:18px;font-weight:800;letter-spacing:2px;">VVS FLAWLESS</div>
                            <div style="margin-top:5px;font-size:10px;letter-spacing:1.5px;color:#ad9a78;">ALERTE RÉSERVATION</div>
                        </td>
                    </tr>

                    <tr>
                        <td class="vvs-padding" style="padding:38px 34px 26px;">
                            <div style="display:inline-block;padding:7px 12px;border:1px solid #8f7b59;border-radius:999px;background:#1b1915;font-size:10px;font-weight:700;letter-spacing:1.5px;color:#d7c29d;">
                                NOUVELLE DEMANDE
                            </div>

                            <h1 style="margin:18px 0 8px;font-family:Georgia,'Times New Roman',serif;font-size:34px;line-height:41px;font-weight:500;color:#f7f3ea;">
                                Une réservation vient d’arriver
                            </h1>

                            <p style="margin:0;font-size:14px;color:#969189;">
                                {{ $reservation->reservation_number }}
                            </p>
                        </td>
                    </tr>

                    <tr>
                        <td class="vvs-padding" style="padding:0 34px 28px;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="border:1px solid #302d27;border-radius:16px;background:#0c0c0c;">
                                <tr>
                                    <td bgcolor="#030303" style="padding:12px;border-bottom:1px solid #302d27;background:#030303;">
                                        <a href="{{ route('dashboard') }}" style="display:block;text-decoration:none;">
                                            <img
                                                class="vvs-email-watch-image"
                                                src="{{ $watchImageUrl }}"
                                                alt="{{ $reservation->watch->name }}"
                                                width="566"
                                                style="display:block;width:100%;max-width:566px;max-height:420px;margin:0 auto;border:0;border-radius:11px;background:#030303;object-fit:contain;"
                                            >
                                        </a>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:22px;border-bottom:1px solid #292722;">
                                        <div style="font-size:10px;letter-spacing:1.5px;color:#a99470;">MODÈLE</div>
                                        <div style="margin-top:8px;font-family:Georgia,'Times New Roman',serif;font-size:22px;line-height:29px;color:#f4efe5;">
                                            {{ $reservation->watch->name }}
                                        </div>
                                    </td>
                                </tr>

                                <tr>
                                    <td style="padding:22px;">
                                        <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0">
                                            <tr>
                                                <td class="vvs-column" width="50%" valign="top" style="padding-right:15px;">
                                                    <div style="font-size:10px;letter-spacing:1.3px;color:#77736c;">MOUVEMENT</div>
                                                    <div style="margin-top:7px;font-size:14px;color:#eee9df;">{{ $reservation->movement }}</div>
                                                </td>
                                                <td class="vvs-column" width="50%" valign="top">
                                                    <div style="font-size:10px;letter-spacing:1.3px;color:#77736c;">PRIX RÉSERVÉ</div>
                                                    <div style="margin-top:7px;font-size:25px;font-weight:800;color:#d8c49e;">
                                                        {{ number_format($reservation->price, 0, ',', ' ') }} €
                                                    </div>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td class="vvs-padding" style="padding:0 34px 28px;">
                            <div style="margin-bottom:13px;font-size:10px;font-weight:700;letter-spacing:1.7px;color:#a99470;">
                                CLIENT
                            </div>

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="border-top:1px solid #292722;">
                                <tr>
                                    <td style="padding:14px 0;color:#77736c;font-size:12px;">Nom</td>
                                    <td align="right" style="padding:14px 0;color:#eee9df;font-size:13px;font-weight:700;">{{ $reservation->customer_name }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:14px 0;border-top:1px solid #24231f;color:#77736c;font-size:12px;">E-mail</td>
                                    <td align="right" style="padding:14px 0;border-top:1px solid #24231f;font-size:13px;">
                                        <a href="mailto:{{ $reservation->email }}" style="color:#d8c49e;text-decoration:none;">{{ $reservation->email }}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:14px 0;border-top:1px solid #24231f;color:#77736c;font-size:12px;">Téléphone</td>
                                    <td align="right" style="padding:14px 0;border-top:1px solid #24231f;font-size:13px;">
                                        <a href="tel:{{ $reservation->phone }}" style="color:#d8c49e;text-decoration:none;">{{ $reservation->phone }}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding:14px 0;border-top:1px solid #24231f;color:#77736c;font-size:12px;">Ville</td>
                                    <td align="right" style="padding:14px 0;border-top:1px solid #24231f;color:#eee9df;font-size:13px;">{{ $reservation->city ?: 'Non renseignée' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:14px 0;border-top:1px solid #24231f;color:#77736c;font-size:12px;">Réception</td>
                                    <td align="right" style="padding:14px 0;border-top:1px solid #24231f;color:#eee9df;font-size:13px;">{{ $reservation->delivery_method }}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    @if ($reservation->message)
                        <tr>
                            <td class="vvs-padding" style="padding:0 34px 28px;">
                                <div style="padding:18px;border-left:2px solid #ad9873;background:#171614;">
                                    <div style="font-size:10px;letter-spacing:1.3px;color:#8e877d;">MESSAGE DU CLIENT</div>
                                    <div style="margin-top:9px;font-size:14px;line-height:23px;color:#cbc6bc;">
                                        {!! nl2br(e($reservation->message)) !!}
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endif

                    <tr>
                        <td class="vvs-padding" style="padding:0 34px 35px;">
                            <a class="vvs-button" href="{{ route('dashboard') }}" style="display:inline-block;padding:15px 23px;border:1px solid #d9c7a5;border-radius:11px;background:#c6ae84;color:#171512;font-size:12px;font-weight:800;letter-spacing:1px;text-decoration:none;">
                                Ouvrir le tableau de bord
                            </a>
                        </td>
                    </tr>

                    <tr>
                        <td class="vvs-padding" style="padding:22px 34px;border-top:1px solid #292722;background:#0b0b0a;font-size:11px;color:#69655f;">
                            Alerte interne VVS FLAWLESS · {{ $reservation->reservation_number }}
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
