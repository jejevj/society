<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class OtpLoginMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $nama;
    public $otp;

    public function __construct($nama, $otp)
    {
        $this->nama = $nama;
        $this->otp = $otp;
    }

    public function build()
    {
        return $this->subject('OTP Login Satu Data Pertahanan')
                    ->view('web.email-otp-login');
    }
}