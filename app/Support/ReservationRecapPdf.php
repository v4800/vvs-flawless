<?php

namespace App\Support;

use App\Models\Reservation;

final class ReservationRecapPdf
{
    public function filename(Reservation $reservation): string
    {
        return $reservation->reservation_number.'-reservation-summary.pdf';
    }

    public function make(Reservation $reservation): string
    {
        $reservation->loadMissing('watch');

        $locale = (string) ($reservation->locale ?: 'fr_BE');
        $previousLocale = app()->getLocale();

        app()->setLocale($locale);

        try {
            $price = $reservation->price !== null
                ? (float) $reservation->price
                : 0.0;

            $deposit = ReservationPayment::depositAmount($price) ?? 0.0;
            $balance = ReservationPayment::balanceAmount($price) ?? 0.0;

            $watchName = (string) (
                $reservation->watch_name_snapshot
                ?: $reservation->watch?->name
                ?: 'VVS FLAWLESS'
            );
            $localizedWatch = null;

            if ($reservation->watch !== null) {
                $localizedWatch = app(WatchCatalog::class)
                    ->localizedWatch($reservation->watch);
                $watchName = (string) $localizedWatch->name;
            }

            $presentedCopy = $localizedWatch?->getAttribute('presented_copy');

            $movement = match ($reservation->movement) {
                'Suisse' => (string) trans('site.movements.suisse'),
                'Japonais' => (string) trans('site.movements.japonais'),
                'Modele presente' => is_array($presentedCopy)
                    ? (string) (
                        $presentedCopy['model_label']
                        ?? $reservation->movement
                    )
                    : (string) $reservation->movement,
                default => (string) $reservation->movement,
            };

            $delivery = $reservation->delivery_method === 'Livraison'
                ? (string) trans('site.product.delivery')
                : (string) trans('site.product.handover');

            $dateFormat = $locale === 'de_BE'
                ? 'd.m.Y H:i'
                : 'd/m/Y H:i';

            $lines = [
                [(string) trans('site.confirmation.title'), 16, true],
                [(string) trans('site.mail.number').' : '.$reservation->reservation_number, 10, false],
                [(string) trans('site.mail.date').' : '.($reservation->created_at?->timezone('Europe/Brussels')->format($dateFormat) ?? '—'), 10, false],
                ['', 10, false],
                [(string) trans('site.confirmation.customer_information'), 11, true],
                [(string) trans('site.mail.name').' : '.$reservation->customer_name, 10, false],
                [(string) trans('site.mail.email').' : '.$reservation->email, 10, false],
                [(string) trans('site.mail.phone').' : '.$reservation->phone, 10, false],
                [(string) trans('site.mail.city').' : '.(
                    $reservation->city
                        ? (string) $reservation->city
                        : (string) trans('site.mail.not_provided')
                ), 10, false],
                ['', 10, false],
                [(string) trans('site.mail.summary'), 11, true],
                [(string) trans('site.mail.watch').' : '.$watchName, 10, false],
                [(string) trans('site.mail.movement').' : '.$movement, 10, false],
                [(string) trans('site.mail.reception_method').' : '.$delivery, 10, false],
                ['', 10, false],
                [(string) trans('site.mail.reserved_price').' : '.$this->money($price, $locale), 10, false],
                [(string) trans('site.mail.deposit').' : '.$this->money($deposit, $locale), 10, true],
                [(string) trans('site.mail.balance').' : '.$this->money($balance, $locale), 10, false],
                ['', 10, false],
                [(string) trans('site.mail.next'), 9, false],
                [(string) trans('site.mail.legal_note'), 8, false],
                [(string) trans('site.mail.document_note'), 8, false],
            ];

            return $this->buildPdf($lines);
        } finally {
            app()->setLocale($previousLocale);
        }
    }

    private function money(float $amount, string $locale): string
    {
        if ($locale === 'en_BE') {
            return '€'.number_format($amount, 2, '.', ',');
        }

        if (in_array($locale, ['nl_BE', 'de_BE'], true)) {
            return number_format($amount, 2, ',', '.').' €';
        }

        return number_format($amount, 2, ',', ' ').' €';
    }

    /**
     * @param  list<array{0:string,1:int,2:bool}>  $lines
     */
    private function buildPdf(array $lines): string
    {
        $logo = $this->logoCommands();
        $content = $logo;

        if ($logo === '') {
            $content .= "BT\n/F2 20 Tf 1 0 0 1 56 790 Tm (VVS FLAWLESS) Tj\nET\n";
        }

        $content .= "BT\n";
        $y = 620;

        foreach ($lines as [$text, $size, $bold]) {
            if ($text === '') {
                $y -= 10;
                continue;
            }

            foreach ($this->wrap($text, $size) as $line) {
                $font = $bold ? '/F2' : '/F1';
                $encoded = $this->pdfText($line);

                $content .= sprintf(
                    "%s %d Tf 1 0 0 1 56 %d Tm (%s) Tj\n",
                    $font,
                    $size,
                    $y,
                    $encoded
                );

                $y -= $size >= 16 ? 27 : 17;
            }
        }

        $content .= "ET\n";

        $objects = [];
        $objects[] = '<< /Type /Catalog /Pages 2 0 R >>';
        $objects[] = '<< /Type /Pages /Kids [3 0 R] /Count 1 >>';
        $objects[] = '<< /Type /Page /Parent 2 0 R /MediaBox [0 0 595 842] /Resources << /Font << /F1 5 0 R /F2 6 0 R >> >> /Contents 4 0 R >>';
        $objects[] = "<< /Length ".strlen($content)." >>\nstream\n".$content."endstream";
        $objects[] = '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica /Encoding /WinAnsiEncoding >>';
        $objects[] = '<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica-Bold /Encoding /WinAnsiEncoding >>';

        $pdf = "%PDF-1.4\n";
        $offsets = [0];

        foreach ($objects as $index => $object) {
            $offsets[] = strlen($pdf);
            $number = $index + 1;
            $pdf .= $number." 0 obj\n".$object."\nendobj\n";
        }

        $xref = strlen($pdf);
        $pdf .= "xref\n0 ".(count($objects) + 1)."\n";
        $pdf .= "0000000000 65535 f \n";

        for ($i = 1; $i <= count($objects); $i++) {
            $pdf .= sprintf("%010d 00000 n \n", $offsets[$i]);
        }

        $pdf .= "trailer\n<< /Size ".(count($objects) + 1)." /Root 1 0 R >>\n";
        $pdf .= "startxref\n".$xref."\n%%EOF";

        return $pdf;
    }

    private function logoCommands(): string
    {
        $path = public_path('images/branding/vvs-flawless-logo.svg');

        if (! is_file($path)) {
            return '';
        }

        $svg = file_get_contents($path);

        if (! is_string($svg) || $svg === '') {
            return '';
        }

        preg_match_all(
            '/<path[^>]*transform="\\s*translate\\(([-\\d.]+),\\s*([-\\d.]+)\\)"[^>]*d="([^"]+)"/s',
            $svg,
            $matches,
            PREG_SET_ORDER
        );

        if (count($matches) < 3) {
            return '';
        }

        $groups = [
            [1.9211538461538462, 642.9677429726271, 609.1560298830245],
            [1.9211538461538462, 645.492266369371, 679.9382617534412],
            [1.9211538461538462, 637.609311053438, 423.43876587676357],
        ];

        $commands = "q\n0.82 0.68 0.43 rg\n";

        foreach (array_slice($matches, 0, 3) as $index => $match) {
            [$scale, $offsetX, $offsetY] = $groups[$index];

            $commands .= $this->svgPathToPdf(
                $match[3],
                (float) $match[1],
                (float) $match[2],
                $scale,
                $offsetX,
                $offsetY
            );
            $commands .= "f*\n";
        }

        return $commands."Q\n";
    }

    private function svgPathToPdf(
        string $path,
        float $translateX,
        float $translateY,
        float $groupScale,
        float $groupX,
        float $groupY
    ): string {
        preg_match_all(
            '/[MLCZ]|-?(?:\\d+\\.\\d+|\\d+|\\.\\d+)(?:[eE][+-]?\\d+)?/',
            $path,
            $matches
        );

        $tokens = $matches[0] ?? [];
        $index = 0;
        $command = null;
        $pdf = '';

        while ($index < count($tokens)) {
            if (in_array($tokens[$index], ['M', 'L', 'C', 'Z'], true)) {
                $command = $tokens[$index];
                $index++;

                if ($command === 'Z') {
                    $pdf .= "h\n";
                    $command = null;
                    continue;
                }
            }

            if ($command === 'M' || $command === 'L') {
                if (! isset($tokens[$index + 1])) {
                    break;
                }

                [$x, $y] = $this->logoPoint(
                    (float) $tokens[$index],
                    (float) $tokens[$index + 1],
                    $translateX,
                    $translateY,
                    $groupScale,
                    $groupX,
                    $groupY
                );

                $pdf .= sprintf(
                    "%.3F %.3F %s\n",
                    $x,
                    $y,
                    $command === 'M' ? 'm' : 'l'
                );
                $index += 2;
                continue;
            }

            if ($command === 'C') {
                if (! isset($tokens[$index + 5])) {
                    break;
                }

                $points = [];

                for ($i = 0; $i < 6; $i += 2) {
                    $points[] = $this->logoPoint(
                        (float) $tokens[$index + $i],
                        (float) $tokens[$index + $i + 1],
                        $translateX,
                        $translateY,
                        $groupScale,
                        $groupX,
                        $groupY
                    );
                }

                $pdf .= sprintf(
                    "%.3F %.3F %.3F %.3F %.3F %.3F c\n",
                    $points[0][0],
                    $points[0][1],
                    $points[1][0],
                    $points[1][1],
                    $points[2][0],
                    $points[2][1]
                );
                $index += 6;
                continue;
            }

            $index++;
        }

        return $pdf;
    }

    /**
     * @return array{0:float,1:float}
     */
    private function logoPoint(
        float $x,
        float $y,
        float $translateX,
        float $translateY,
        float $groupScale,
        float $groupX,
        float $groupY
    ): array {
        $svgX = $groupScale * ($x + $translateX) + $groupX;
        $svgY = $groupScale * ($y + $translateY) + $groupY;

        $scale = 300 / 1280;
        $boxX = (595 - 300) / 2;
        $boxTop = 825;

        return [
            $boxX + ($svgX * $scale),
            $boxTop - ($svgY * $scale),
        ];
    }

    /**
     * @return list<string>
     */
    private function wrap(string $text, int $size): array
    {
        $limit = $size >= 16 ? 48 : ($size >= 11 ? 72 : 86);
        $wrapped = wordwrap($text, $limit, "\n", false);

        return explode("\n", $wrapped);
    }

    private function pdfText(string $value): string
    {
        $value = str_replace(["\r", "\n"], ' ', $value);
        $value = iconv('UTF-8', 'Windows-1252//TRANSLIT', $value) ?: $value;

        return str_replace(
            ['\\', '(', ')'],
            ['\\\\', '\\(', '\\)'],
            $value
        );
    }
}
