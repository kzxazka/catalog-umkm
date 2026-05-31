<?php

namespace App\Mail;

use App\Models\MitraApplication;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class MitraApplicationSubmitted extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(public MitraApplication $application) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: '[Portal UMKM] Pengajuan Kemitraan Baru — ' . $this->application->business_name,
        );
    }

    public function content(): Content
    {
        return new Content(view: 'emails.mitra.submitted');
    }
}
