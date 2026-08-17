<?php // Indique que ce fichier contient du code PHP

namespace App\Services; // Définit l'espace de noms du service

use App\Models\User; // Importe le modèle User
use App\Models\PasswordResetCode; // Importe le modèle des codes de réinitialisation
use App\Notifications\PasswordResetCodeNotification; // Importe la notification qui envoie le code
use Illuminate\Support\Facades\Hash; // Permet de hacher et vérifier le code


class PasswordResetService // Déclare le service de réinitialisation du mot de passe
{
    /**
     * Génère et envoie un code de réinitialisation.
     */
    public function sendCode(User $user): void
    {
        // Supprime les anciens codes de réinitialisation de cet utilisateur
        PasswordResetCode::where('user_id', $user->id)->delete();

        // Génère un nouveau code aléatoire de 6 chiffres
        $code = (string) random_int(100000, 999999);

        // Enregistre le code haché dans la base de données
        PasswordResetCode::create([
            'user_id' => $user->id, // Associe le code à l'utilisateur
            'code' => Hash::make($code), // Hache le code avant de l'enregistrer
            'expires_at' => now()->addMinutes(10), // Définit une durée de validité de 10 minutes
        ]);

        // Envoie le code par email à l'utilisateur
        $user->notify(new PasswordResetCodeNotification($code));
    }


    /**
     * Vérifie si le code fourni par l'utilisateur est valide.
     */
    public function verifyCode(User $user, string $code): bool
    {
        // Recherche le dernier code de réinitialisation de l'utilisateur
        $resetCode = PasswordResetCode::where('user_id', $user->id)
            ->latest()
            ->first();

        // Vérifie si aucun code n'existe
        if (!$resetCode) {
            return false; // Indique que le code n'est pas valide
        }

        // Vérifie si le code a dépassé sa date d'expiration
        if ($resetCode->expires_at->isPast()) {
            // Supprime le code expiré
            $resetCode->delete();

            // Indique que le code n'est plus valide
            return false;
        }

        // Vérifie si le code fourni correspond au code haché enregistré
        if (!Hash::check($code, $resetCode->code)) {
            return false; // Indique que le code est incorrect
        }

        // Ne supprime PAS le code ici
        // Il doit encore être disponible pour l'étape de réinitialisation

        return true; // Indique que le code est valide
    }


    /**
     * Supprime le code de réinitialisation après utilisation.
     */
    public function deleteCode(User $user): void
    {
        // Supprime tous les codes de réinitialisation de l'utilisateur
        PasswordResetCode::where('user_id', $user->id)->delete();
    }
}