<?php

namespace App\Services;

use App\Models\User;
use App\Models\EmailVerificationCode;
use App\Notifications\EmailVerificationNotification;

class EmailVerificationService
{
    /**
     * Génère et envoie un nouveau code de vérification.
     */
    public function sendCode(User $user): void
    {
        // Supprime les anciens codes de vérification de l'utilisateur
        $user->emailVerificationCodes()->delete();

        // Génère un code aléatoire de 6 chiffres
        $code = (string) random_int(100000, 999999);

        // Enregistre le nouveau code dans la base de données
        EmailVerificationCode::create([
            'user_id' => $user->id,
            'code' => $code,
            'expires_at' => now()->addMinutes(10),
        ]);

        // Envoie le code par email
        $user->notify(new EmailVerificationNotification($code));
    }
}