<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

use Illuminate\Auth\Notifications\VerifyEmail;

class VerifyEmailCustom extends VerifyEmail
{
    public function toMail($notifiable)
    {
        $url = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('Verifica tu cuenta en SkinMarket')
            ->greeting('Hola ' . $notifiable->nombre)
            ->line('Gracias por registrarte en nuestra plataforma.')
            ->line('Solo falta un paso para activar tu cuenta.')
            ->action('Verificar cuenta', $url)
            ->line('Si no creaste esta cuenta, puedes ignorar este correo.');
    }
}
