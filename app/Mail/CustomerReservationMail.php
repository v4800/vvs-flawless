<?php

namespace App\Mail;

use App\Models\Reservation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class CustomerReservationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Reservation $reservation
    ) {
        $this->reservation->loadMissing('watch');
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Récapitulatif de votre réservation VVS FLAWLESS — '
                .$this->reservation->reservation_number
        );
    }

    public function content(): Content
    {
        return new Content(
            markdown: 'mail.reservations.customer'
        );
    }

    /**
     * @return array<int, Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
