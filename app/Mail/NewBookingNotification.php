<?php

namespace App\Mail;

use App\Models\Booking; // <--- Import Model
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NewBookingNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $booking; // <--- Siapkan variabel penampung data

    // Terima data booking saat surat dibuat
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    // Judul Email
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Ada Booking Baru: ' . $this->booking->service_type,
        );
    }

    // Isi Surat (View)
    public function content(): Content
    {
        return new Content(
            view: 'emails.new_booking', // Kita akan buat file ini sebentar lagi
        );
    }
}
