<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TwoFactorCode extends Mailable
{
    use Queueable, SerializesModels;

    public $code; // This variable holds the OTP

    public function __construct($code)
    {
        $this->code = $code;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Login Code - Kape Ni Asero',
        );
    }

    public function content(): Content
    {
        // This points to the HTML design file
        return new Content(
            view: 'emails.twoFactorCode', 
        );
    }
}