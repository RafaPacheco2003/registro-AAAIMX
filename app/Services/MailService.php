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
}