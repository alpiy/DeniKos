<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

class VerifyEmailDeniKos extends VerifyEmail
{
    use Queueable;

    /**
     * Get the mail representation of the notification.
     */
    public function toMail($notifiable): MailMessage
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Verifikasi Email Akun DeniKos')
            ->greeting('Halo ' . $notifiable->name . '!')
            ->line('Selamat datang di DeniKos! Untuk mengaktifkan akun Anda, silakan verifikasi alamat email dengan mengklik tombol di bawah ini.')
            ->action('Verifikasi Email', $verificationUrl)
            ->line('Link verifikasi ini akan kedaluwarsa dalam ' . Config::get('auth.verification.expire', 60) . ' menit.')
            ->line('Jika Anda tidak membuat akun DeniKos, abaikan email ini.')
            ->salutation('Salam hangat, Tim DeniKos');
    }

    /**
     * Get the verification URL for the given notifiable.
     */
    protected function verificationUrl($notifiable)
    {
        return URL::temporarySignedRoute(
            'auth.verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
    }
}
