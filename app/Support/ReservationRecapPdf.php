<?php

namespace App\Support;

use App\Models\Reservation;

final class ReservationRecapPdf
{
    public function filename(Reservation $reservation): string
    {
        return 'VVS-FLAWLESS-'.$reservation->reservation_number.'-recapitulatif.pdf';
    }

    public function make(Reservation $reservation): string
    {
        $reservation->loadMissing('watch');

        $price = $reservation->price !== null ? (float) $reservation->price : 0.0;
        $deposit = ReservationPayment::depositAmount($price) ?? 0.0;
        $balance = ReservationPayment::balanceAmount($price) ?? 0.0;
        $watchName = $reservation->watch_name_snapshot
            ?: $reservation->watch?->name
            ?: 'Montre VVS FLAWLESS';

        $lines = [
            ['VVS FLAWLESS', 22, true],
            ['MOISSANITE VVS · BELGIQUE', 9, false],
            ['', 10, false],
            ['RÉCAPITULATIF DE RÉSERVATION', 16, true],
            ['Référence : '.$reservation->reservation_number, 10, false],
            ['Date : '.($reservation->created_at?->timezone('Europe/Brussels')->format('d/m/Y H:i') ?? '—'), 10, false],
            ['', 10, false],
            ['CLIENT', 11, true],
            ['Nom : '.$reservation->customer_name, 10, false],
            ['E-mail : '.$reservation->email, 10, false],
            ['Téléphone : '.$reservation->phone, 10, false],
            ['Ville : '.($reservation->city ?: '—'), 10, false],
            ['', 10, false],
            ['RÉSERVATION', 11, true],
            ['Montre : '.$watchName, 10, false],
            ['Mouvement : '.$reservation->movement, 10, false],
            ['Réception : '.$reservation->delivery_method, 10, false],
            ['', 10, false],
            ['MONTANTS', 11, true],
            ['Prix total : '.$this->money($price), 10, false],
            ['Acompte fixe : '.$this->money($deposit), 10, true],
            ['Solde restant : '.$this->money($balance), 10, false],
            ['', 10, false],
            ['Aucun paiement n’est effectué automatiquement sur le site.', 9, false],
            ['La préparation débute après validation des détails et confirmation de l’acompte.', 9, false],
            ['Le solde est organisé avec le client lorsque la montre est prête.', 9, false],
            ['', 10, false],
            ['Ce document est un récapitulatif de réservation et non une facture fiscale.', 8, false],
        ];

        return $this->buildPdf($lines);
    }

    private function money(float $amount): string
    {
        return number_format($amount, 2, ',', ' ').' €';
    }

    /**
     * @param  list<array{0:string,1:int,2:bool}>  $lines
     */
    private function buildPdf(array $lines): string
    {
        $content = "BT\n";
        $y = 790;

        foreach ($lines as [$text, $size, $bold]) {
            if ($text === '') {
                $y -= 10;
                continue;
            }

            $font = $bold ? '/F2' : '/F1';
            $encoded = $this->pdfText($text);

            $content .= sprintf(
                "%s %d Tf 1 0 0 1 56 %d Tm (%s) Tj\n",
                $font,
                $size,
                $y,
                $encoded
            );

            $y -= $size >= 16 ? 28 : 18;
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
