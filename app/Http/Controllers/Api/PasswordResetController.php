<?php // Indique que ce fichier contient du code PHP

namespace App\Http\Controllers\Api; // Définit l'espace de noms du contrôleur API

use App\Http\Controllers\Controller; // Importe le contrôleur principal de Laravel
use App\Models\User; // Importe le modèle User
use App\Services\PasswordResetService; // Importe le service de réinitialisation du mot de passe
use Illuminate\Http\Request; // Permet de récupérer les données envoyées dans la requête
use Illuminate\Support\Facades\Hash; // Permet de vérifier et de hacher les mots de passe
use App\Models\PasswordResetCode; // Importe le modèle des codes de réinitialisation


class PasswordResetController extends Controller // Déclare le contrôleur de réinitialisation du mot de passe
{
    /**
     * Envoie un code de réinitialisation à l'adresse email.
     */
    public function forgot(Request $request, PasswordResetService $passwordResetService)
    {
        // Vérifie que l'adresse email est présente et valide
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        // Recherche l'utilisateur correspondant à l'adresse email
        $user = User::where('email', $request->email)->first();

        // Vérifie si l'utilisateur existe
        if (!$user) {
            // Retourne un message générique pour éviter de révéler si un compte existe
            return response()->json([
                'message' => 'Si cette adresse email existe, un code de réinitialisation a été envoyé.',
            ]);
        }

        // Génère et envoie le code de réinitialisation
        $passwordResetService->sendCode($user);

        // Retourne une réponse de confirmation
        return response()->json([
            'message' => 'Un code de réinitialisation a été envoyé à votre adresse email.',
        ]);
    }


    /**
     * Vérifie le code de réinitialisation.
     */
    public function verify(Request $request, PasswordResetService $passwordResetService)
    {
        // Vérifie les données reçues
        $request->validate([
            'email' => ['required', 'email'],
            'code' => ['required', 'digits:6'],
        ]);

        // Recherche l'utilisateur avec son adresse email
        $user = User::where('email', $request->email)->first();

        // Vérifie si l'utilisateur existe
        if (!$user) {
            return response()->json([
                'message' => 'Le code de vérification est incorrect.',
            ], 422);
        }

        // Vérifie le code grâce au service
        $isValid = $passwordResetService->verifyCode(
            $user,
            $request->code
        );

        // Vérifie si le code est incorrect ou expiré
        if (!$isValid) {
            return response()->json([
                'message' => 'Le code de vérification est incorrect ou expiré.',
            ], 422);
        }

        // Retourne une réponse de confirmation
        return response()->json([
            'message' => 'Code vérifié avec succès.',
        ]);
    }


    /**
     * Réinitialise le mot de passe de l'utilisateur.
     */
    public function reset(Request $request)
    {
        // Vérifie les données envoyées
        $request->validate([
            'email' => ['required', 'email'],
            'code' => ['required', 'digits:6'],
            'password' => ['required', 'string', 'min:8', 'confirmed'],
        ]);

        // Recherche l'utilisateur avec son adresse email
        $user = User::where('email', $request->email)->first();

        // Vérifie si l'utilisateur existe
        if (!$user) {
            return response()->json([
                'message' => 'Les informations de réinitialisation sont incorrectes.',
            ], 422);
        }

        // Recherche le dernier code de réinitialisation
        $resetCode = PasswordResetCode::where('user_id', $user->id)
            ->latest()
            ->first();

        // Vérifie si le code existe
        if (!$resetCode) {
            return response()->json([
                'message' => 'Aucun code de réinitialisation valide.',
            ], 422);
        }

        // Vérifie si le code est encore valide
        if ($resetCode->expires_at->isPast()) {
            // Supprime le code expiré
            $resetCode->delete();

            // Retourne une erreur
            return response()->json([
                'message' => 'Le code de réinitialisation a expiré.',
            ], 422);
        }

        // Vérifie que le code fourni correspond au code enregistré
        if (!Hash::check($request->code, $resetCode->code)) {
            return response()->json([
                'message' => 'Le code de réinitialisation est incorrect.',
            ], 422);
        }

        // Met à jour le mot de passe avec le nouveau mot de passe
        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Supprime le code après son utilisation
        $resetCode->delete();

        // Retourne une réponse de confirmation
        return response()->json([
            'message' => 'Mot de passe réinitialisé avec succès.',
        ]);
    }
}