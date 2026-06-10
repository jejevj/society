<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PesertaRegistrasiMail extends Mailable
{
    use Queueable, SerializesModels;

    public string $namaPeserta;
    public string $emailPeserta;
    public string $namaEvent;
    public string $registerUrl;
    public string $token;

    public function __construct(
        string $namaPeserta,
        string $emailPeserta,
        string $namaEvent,
        string $registerUrl,
        string $token
    ) {
        $this->namaPeserta  = $namaPeserta;
        $this->emailPeserta = $emailPeserta;
        $this->namaEvent    = $namaEvent;
        $this->registerUrl  = $registerUrl;
        $this->token        = $token;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Selamat! Anda Terdaftar di ' . $this->namaEvent . ' - Lengkapi Akun Anda',
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.peserta-registrasi',
        );
    }
}
