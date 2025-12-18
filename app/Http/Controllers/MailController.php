<?php

namespace App\Http\Controllers;

use App\Mail\TestMail;
use App\MailSetting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;

class MailController extends Controller
{
    public function sendEmail()
    {
        // Lade Mail-Einstellungen des Users
        $mailSettings = MailSetting::where('user_id', 1)->first();

        if (!$mailSettings) {
            return "Keine Mail-Konfiguration für diesen User.";
        }

        // Konfiguration dynamisch setzen
        Config::set('mail.mailers.smtp', [
            'transport' => 'smtp',
            'host' => $mailSettings->host,
            'port' => $mailSettings->port,
            'encryption' => $mailSettings->encryption,
            'username' => $mailSettings->username,
            'password' => $mailSettings->password,
        ]);

        Config::set('mail.from', [
            'address' => $mailSettings->from_address,
            'name' => $mailSettings->from_name,
        ]);
        // Config::set('mail.mailers.smtp', [
        //     'transport' => 'smtp',
        //     'host' => 'smtp.gmail.com',
        //     'port' => 587,
        //     'encryption' => 'tls',
        //     'username' => 'neoburgportal@gmail.com',
        //     'password' => 'kgafljittinibdap',
        // ]);

        // Config::set('mail.from', [
        //     'address' => 'neoburgportal@gmail.com',
        //     'name' => 'neoburgportal',
        // ]);

        // // E-Mail senden
         Mail::to('dardanzyrapi@gmail.com')->send(new TestMail('neoburgportal@gmail.com', 'neoburgportal'));

        return "E-Mail wurde mit benutzerdefinierten SMTP-Daten gesendet!";
    }
}

