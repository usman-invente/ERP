<?php

namespace App\Providers;

use App\Models\MailSetting;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        // Falls der User eingeloggt ist, lade Mail-Config aus der DB
        if (Auth::check()) {
            $mailSettings = MailSetting::where('user_id', Auth::id())->first();
            
            if ($mailSettings) {
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
            }
        }
    }
}
