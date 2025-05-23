<?php

namespace App\Providers;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }

        ResetPassword::toMailUsing(function ($notifiable, $token) {
            $url = url(route('password.reset', [
                'token' => $token,
                'email' => $notifiable->getEmailForPasswordReset(),
            ], false));

            return (new MailMessage)
                ->subject('Wachtwoord opnieuw instellen Lotus Planner')
                ->greeting('Hallo!')
//                ->line('Lotuskring de Blauwvinger heeft een nieuw systeem voor het indienen van lotus aanvragen.')
//                ->line('Hiervoor is het nodig dat bestaande klanten een nieuw wachtwoord in moeten stellen.')
                ->line('Je hebt aangegeven je wachtwoord opnieuw te willen instellen.')
                ->action('Stel wachtwoord opnieuw in', $url)
//                ->line('Heb je dit niet aangevraagd? Dan hoef je niets te doen.')
                ->line($url)

                ->salutation('Met vriendelijke groet, Lotuskring de Blauwvinger');

        });
    }
}
