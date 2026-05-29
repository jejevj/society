<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\DB;

class AppMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    protected $viewFile;
    protected $dataView;
    protected $subjectMail;
    protected $attachmentsFile;

    public function __construct(
        $viewFile,
        $dataView = [],
        $subjectMail = '',
        $attachmentsFile = []
    ) {
        $this->viewFile = $viewFile;
        $this->dataView = $dataView;
        $this->subjectMail = $subjectMail;
        $this->attachmentsFile = $attachmentsFile;
    }

    protected function setDynamicMailConfig()
    {
        $setting = DB::table('app_email')->first();

        config([
            'mail.default' => 'smtp',

            'mail.mailers.smtp.transport' => 'smtp',
            'mail.mailers.smtp.host' => $setting->smtp_host,
            'mail.mailers.smtp.port' => $setting->smtp_port,
            'mail.mailers.smtp.encryption' =>
                $setting->smtp_encryption != ''
                    ? $setting->smtp_encryption
                    : null,

            'mail.mailers.smtp.username' => $setting->smtp_username,
            'mail.mailers.smtp.password' => $setting->smtp_password,

            'mail.from.address' => $setting->smtp_from_address,
            'mail.from.name' => $setting->smtp_from_name,
        ]);
    }

    public function build()
    {
        \Log::info('VIEW MAIL:', [
            'view' => $this->viewFile
        ]);

        $this->setDynamicMailConfig();

        $mail = $this->subject($this->subjectMail)
            ->view($this->viewFile)
            ->with($this->dataView);

        if (!empty($this->attachmentsFile)) {

            foreach ($this->attachmentsFile as $file) {

                if (file_exists($file)) {
                    $mail->attach($file);
                }
            }
        }

        return $mail;
    }
}