<?php

namespace App\Notifications;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OrderCreatedNotification extends Notification
{
    use Queueable;

    /**
     * Commande créée.
     */
    protected Order $order;

    /**
     * Crée une nouvelle notification.
     */
    public function __construct(Order $order)
    {
        // Enregistre la commande reçue
        $this->order = $order;
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
            ->subject('Confirmation de votre commande - ShopMaster')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Votre commande a été créée avec succès.')
            ->line('Numéro de commande : ' . $this->order->id)
            ->line('Montant total : ' . $this->order->total)
            ->line('Statut : ' . $this->order->status)
            ->line('Merci pour votre commande.')
            ->salutation('L’équipe ShopMaster');
    }
}
