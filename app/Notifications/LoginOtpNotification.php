<?php

namespace App\Notifications;

use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LoginOtpNotification extends Notification
{
    public function __construct(private readonly string $code)
    {
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Code de connexion - ShopMaster')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Voici votre code de connexion à votre compte ShopMaster.')
            ->line('')
            ->line('Code : ' . $this->code)
            ->line('')
            ->line('Ce code est valable pendant 10 minutes.')
            ->line('Si vous n’êtes pas à l’origine de cette demande, vous pouvez ignorer cet email.')
            ->salutation('L’équipe ShopMaster');
    }
}
