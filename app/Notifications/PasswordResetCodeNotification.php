<?php // Indique que ce fichier contient du code PHP

namespace App\Notifications; // Définit l'espace de noms de la notification

use Illuminate\Bus\Queueable; // Permet de mettre la notification en file d'attente
use Illuminate\Notifications\Messages\MailMessage; // Permet de construire le contenu de l'email
use Illuminate\Notifications\Notification; // Classe de base des notifications Laravel

class PasswordResetCodeNotification extends Notification // Déclare la notification pour la réinitialisation du mot de passe
{
    use Queueable; // Active les fonctionnalités de mise en file d'attente


    /**
     * Contient le code de réinitialisation du mot de passe.
     */
    protected string $code; // Stocke le code à 6 chiffres


    /**
     * Crée une nouvelle notification.
     */
    public function __construct(string $code) // Reçoit le code généré par le service
    {
        $this->code = $code; // Stocke le code dans la notification
    }


    /**
     * Définit les canaux utilisés pour envoyer la notification.
     */
    public function via(object $notifiable): array // Détermine comment la notification sera envoyée
    {
        return ['mail']; // Indique que la notification sera envoyée par email
    }


    /**
     * Définit le contenu de l'email.
     */
    public function toMail(object $notifiable): MailMessage // Construit le message envoyé par email
    {
        return (new MailMessage) // Crée un nouveau message email Laravel
            ->subject('Réinitialisation de votre mot de passe - ShopMaster') // Définit le sujet de l'email
            ->greeting('Bonjour ' . $notifiable->name . ',') // Affiche le nom de l'utilisateur dans le message
            ->line('Vous avez demandé la réinitialisation de votre mot de passe.') // Explique pourquoi l'email est envoyé
            ->line('Votre code de réinitialisation est :') // Présente le code à l'utilisateur
            ->line($this->code) // Affiche le code de réinitialisation à 6 chiffres
            ->line('Ce code est valable pendant 10 minutes.') // Indique la durée de validité du code
            ->line('Si vous n\'êtes pas à l\'origine de cette demande, vous pouvez ignorer cet email.') // Informe l'utilisateur en cas de demande non effectuée
            ->salutation('L\'équipe ShopMaster'); // Ajoute la signature de ShopMaster
    }


    /**
     * Définit les données de la notification.
     */
    public function toArray(object $notifiable): array // Transforme la notification en tableau si nécessaire
    {
        return [
            'code' => $this->code, // Retourne le code dans les données de la notification
        ];
    }
}