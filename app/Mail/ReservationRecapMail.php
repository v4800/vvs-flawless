<?php

namespace App\Mail;

use App\Models\Reservation;
use App\Support\ReservationRecapPdf;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ReservationRecapMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public Reservation $reservation) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: (string) trans(
                'site.mail.subject',
                ['number' => $this->reservation->reservation_number],
                (string) ($this->reservation->locale ?: config('app.locale'))
            )
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'mail.reservations.recap'
        );
    }

    /**
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        $pdf = app(ReservationRecapPdf::class);

        return [
            Attachment::fromData(
                fn (): string => $pdf->make($this->reservation),
                $pdf->filename($this->reservation)
            )->withMime('application/pdf'),
        ];
    }
}
