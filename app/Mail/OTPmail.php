<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OTPmail extends Mailable
{
    use Queueable, SerializesModels;

    public string $otp;

    /**
     * Crée une nouvelle instance du message.
     */
    public function __construct(string $otp)
    {
        $this->otp = $otp;
    }

    /**
     * Sujet de l’email.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Votre code de vérification',
        );
    }

    /**
     * Vue et données à passer à l’email.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.otp', // Crée cette vue dans resources/views/emails/otp.blade.php
            with: [
                'otp' => $this->otp
            ],
        );
    }

    public function attachments(): array
    {
        return [];
    }
}
