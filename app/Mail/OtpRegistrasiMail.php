<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class OtpRegistrasiMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $otp;
    public string $nama;

    public function __construct(string $otp, string $nama)
    {
        $this->otp  = $otp;
        $this->nama = $nama;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Kode OTP Registrasi Anda',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.otp-registrasi',
        );
    }
}
