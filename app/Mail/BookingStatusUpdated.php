<?php

namespace App\Mail;

use App\Models\Booking; // <--- Penting: Import Model Booking
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class BookingStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public $booking; // <--- Variabel untuk menampung data

    /**
     * Terima data booking saat surat dibuat
     */
    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    /**
     * Atur Judul Email (Subject)
     */
    public function envelope(): Envelope
    {
        // Logika Judul: Kalau Confirmed jadi "Disetujui", kalau Cancelled jadi "Ditolak"
        $status = ucfirst($this->booking->status);

        if ($this->booking->status == 'confirmed') {
            $status = 'Disetujui ✅';
        } elseif ($this->booking->status == 'cancelled') {
            $status = 'Ditolak ❌';
        }

        return new Envelope(
            subject: 'Update Status Booking: ' . $status,
        );
    }

    /**
     * Tentukan isi suratnya ambil dari file mana
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.status_updated', // <--- Pastikan nama file view sesuai
        );
    }

    /**
     * Lampiran (Kosongkan saja)
     */
    public function attachments(): array
    {
        return [];
    }
}