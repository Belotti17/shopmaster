<?php

namespace App\Notifications; // Emplacement de la notification

use Illuminate\Bus\Queueable; // Permet d'utiliser les fonctionnalités de file d'attente
use Illuminate\Notifications\Messages\MailMessage; // Permet de construire l'email
use Illuminate\Notifications\Notification; // Classe de base des notifications

class EmailVerificationNotification extends Notification
{
    use Queueable; // Active les fonctionnalités de file d'attente

    /**
     * Crée une nouvelle notification.
     */
    public function __construct(
        public string $code // Reçoit le code de vérification
    ) {
        //
    }

    /**
     * Définit les canaux utilisés pour envoyer la notification.
     */
    public function via(object $notifiable): array
    {
        // Envoie la notification par email
        return ['mail'];
    }

    /**
     * Construit l'email de vérification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        // Construit le contenu de l'email
        return (new MailMessage)
            ->subject('Vérification de votre adresse email - ShopMaster')

            // Message de bienvenue
            ->greeting('Bonjour ' . $notifiable->name . ',')

            // Explique pourquoi l'utilisateur reçoit cet email
            ->line('Merci d’avoir créé votre compte ShopMaster.')

            // Présente le code
            ->line('Votre code de vérification est :')

            // Affiche le code de 6 chiffres
            ->line('**' . $this->code . '**')

            // Indique la durée de validité
            ->line('Ce code est valable pendant 10 minutes.')

            // Message de sécurité
            ->line('Si vous n’êtes pas à l’origine de cette inscription, ignorez simplement cet email.')

            // Signature
            ->salutation('L’équipe ShopMaster');
    }

    /**
     * Représentation de la notification sous forme de tableau.
     */
    public function toArray(object $notifiable): array
    {
        // Retourne les informations de la notification
        return [
            'code' => $this->code,
        ];
    }
}