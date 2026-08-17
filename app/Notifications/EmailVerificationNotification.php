<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailVerificationNotification extends Notification
{
    use Queueable;

    /**
     * Code de vérification.
     */
    protected string $code;

    /**
     * Crée une nouvelle notification.
     */
    public function __construct(string $code)
    {
        // Enregistre le code reçu
        $this->code = $code;
    }

    /**
     * Définit les canaux d'envoi.
     */
    public function via(object $notifiable): array
    {
        // Envoie la notification par email
        return ['mail'];
    }

    /**
     * Définit le contenu de l'email.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Vérification de votre adresse email - ShopMaster')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Merci de vous être inscrit sur ShopMaster.')
            ->line('Voici votre code de vérification :')
            ->line($this->code)
            ->line('Ce code est valable pendant 10 minutes.')
            ->line('Si vous n’êtes pas à l’origine de cette inscription, vous pouvez ignorer cet email.');
    }

    /**
     * Représentation de la notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'code' => $this->code,
        ];
    }
}