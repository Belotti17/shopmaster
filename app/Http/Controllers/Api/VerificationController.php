<?php

namespace App\Http\Controllers\Api; // Namespace du contrôleur API

use App\Http\Controllers\Controller; // Contrôleur principal Laravel
use App\Http\Requests\VerifyEmailRequest; // Validation du code de vérification
use App\Models\User; // Modèle utilisateur
use App\Models\EmailVerificationCode; // Modèle des codes de vérification

class VerificationController extends Controller
{
    /**
     * Vérifie le code envoyé par l'utilisateur.
     */
    public function verify(VerifyEmailRequest $request)
    {
        // Recherche l'utilisateur grâce à son adresse email
        $user = User::where('email', $request->email)->first();

        // Vérifie si l'utilisateur a déjà confirmé son adresse email
        if ($user->email_verified_at !== null) {
            return response()->json([
                'message' => 'Cette adresse email est déjà vérifiée.',
            ], 400);
        }

        // Recherche le code de vérification correspondant à l'utilisateur
        $verificationCode = EmailVerificationCode::where('user_id', $user->id)
            ->where('code', $request->code)
            ->latest()
            ->first();

        // Vérifie si le code existe
        if (!$verificationCode) {
            return response()->json([
                'message' => 'Le code de vérification est incorrect.',
            ], 422);
        }

        // Vérifie si le code est expiré
        if ($verificationCode->expires_at->isPast()) {
            // Supprime le code expiré
            $verificationCode->delete();

            return response()->json([
                'message' => 'Le code de vérification a expiré.',
            ], 422);
        }

        // Marque l'adresse email comme vérifiée
        $user->email_verified_at = now();

        // Enregistre la modification dans la base de données
        $user->save();

        // Supprime le code qui vient d'être utilisé
        $verificationCode->delete();

        // Retourne une réponse de confirmation
        return response()->json([
            'message' => 'Adresse email vérifiée avec succès.',
            'user' => $user->fresh(),
        ]);
    }
}