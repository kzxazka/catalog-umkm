<?php

namespace App\Mail;

use App\Models\MitraApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MitraStatusUpdated extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public MitraApplication $application,
        public string $status // 'approved' | 'rejected'
    ) {}

    public function envelope(): Envelope
    {
        $subject = $this->status === 'approved'
            ? '[Portal UMKM] Selamat! Pengajuan Mitra Anda Disetujui'
            : '[Portal UMKM] Pembaruan Status Pengajuan Kemitraan';

        return new Envelope(subject: $subject);
    }

    public function content(): Content
    {
        return new Content(
            view: $this->status === 'approved'
                ? 'emails.mitra.approved'
                : 'emails.mitra.rejected'
        );
    }
}
