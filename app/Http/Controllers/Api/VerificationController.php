<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\EmailVerificationRequest;

class VerificationController extends Controller
{
    // Vérifie l'adresse email de l'utilisateur
    public function verify(EmailVerificationRequest $request)
    {
        // Vérifie si l'adresse email est déjà vérifiée
        if ($request->user()->hasVerifiedEmail()) {

            // Retourne une réponse JSON pour informer l'utilisateur
            return response()->json([
                'message' => 'Votre adresse email est déjà vérifiée.',
            ]);
        }

        // Marque l'adresse email de l'utilisateur comme vérifiée
        $request->user()->markEmailAsVerified();

        // Retourne une réponse JSON après la vérification
        return response()->json([
            'message' => 'Votre adresse email a été vérifiée avec succès.',
        ]);
    }
}