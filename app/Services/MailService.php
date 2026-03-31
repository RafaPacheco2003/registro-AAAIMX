<?php

namespace App\Services;


use Illuminate\Support\Facades\Mail;

class MailService{

    public function sendTestEmail(string $to): void
    {
        Mail::raw('Este es un correo de prueba desde la API de RoboRage.', function ($message) use ($to) {
            $message->to($to)
                ->subject('Correo de prueba - RoboRage');
        });
    }


    public function sendRegistrationPdf(string $to, string $pdfBinary, string $attachmentFilename): void
    {
        Mail::raw(
            'Adjuntamos el comprobante de registro de RoboRage.',
            function ($message) use ($to, $pdfBinary, $attachmentFilename) {
                $message->to($to)
                    ->subject('Comprobante de registro - RoboRage')
                    ->attachData($pdfBinary, $attachmentFilename, ['mime' => 'application/pdf']);
            }
        );
    }
}