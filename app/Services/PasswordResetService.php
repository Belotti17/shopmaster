<?php

namespace App\Services;

use App\Models\User;
use App\Models\PasswordResetCode;
use App\Notifications\PasswordResetCodeNotification;
use Illuminate\Support\Facades\Hash;

class PasswordResetService
{
    /**
     * Génère et envoie un code de réinitialisation du mot de passe.
     */
    public function sendCode(User $user): void
    {
        // Supprime les anciens codes de cet utilisateur.
        PasswordResetCode::where('user_id', $user->id)->delete();

        // Génère un code aléatoire à 6 chiffres.
        $code = (string) random_int(100000, 999999);

        // Enregistre le code dans la base de données.
        PasswordResetCode::create([
            'user_id' => $user->id,
            'code' => Hash::make($code),
            'expires_at' => now()->addMinutes(10),
        ]);

        // Envoie le code par email.
        $user->notify(new PasswordResetCodeNotification($code));
    }

    /**
     * Vérifie si le code fourni est valide.
     */
    public function verifyCode(User $user, string $code): bool
    {
        // Recherche le dernier code de l'utilisateur.
        $verificationCode = PasswordResetCode::where('user_id', $user->id)
            ->latest()
            ->first();

        // Vérifie que le code existe.
        if (!$verificationCode) {
            return false;
        }

        // Vérifie que le code n'est pas expiré.
        if ($verificationCode->expires_at->isPast()) {
            $verificationCode->delete();

            return false;
        }

        // Vérifie que le code fourni correspond au code enregistré.
        if (!Hash::check($code, $verificationCode->code)) {
            return false;
        }

        // Supprime le code après une vérification réussie.
        $verificationCode->delete();

        return true;
    }
}