<?php

namespace App\Models; // Indique que ce modèle appartient à App\Models

use Illuminate\Database\Eloquent\Model; // Importe le modèle Eloquent de Laravel
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Permet de définir une relation belongsTo
use App\Models\User; // Importe le modèle User

class EmailVerificationCode extends Model
{
    // Définit les champs pouvant être remplis automatiquement
    protected $fillable = [
        'user_id',
        'code',
        'expires_at',
    ];

    // Convertit automatiquement expires_at en objet de type date
    protected function casts(): array
    {
        return [
            'expires_at' => 'datetime',
        ];
    }

    // Définit la relation entre le code et l'utilisateur
    public function user(): BelongsTo
    {
        // Un code de vérification appartient à un utilisateur
        return $this->belongsTo(User::class);
    }
}