<?php

namespace App\Models; // Namespace du modèle

use Illuminate\Database\Eloquent\Model; // Classe de base du modèle Laravel
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Permet de définir la relation avec User

class EmailVerificationCode extends Model
{
    /**
     * Champs pouvant être remplis automatiquement.
     */
    protected $fillable = [
        'user_id',    // Identifiant de l'utilisateur
        'code',       // Code de vérification à 6 chiffres
        'expires_at', // Date d'expiration du code
    ];

    /**
     * Convertit automatiquement expires_at en objet date.
     */
    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
        ];
    }

    /**
     * Définit la relation avec l'utilisateur.
     *
     * Un code appartient à un seul utilisateur.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}