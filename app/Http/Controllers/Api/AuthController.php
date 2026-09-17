<?php

namespace App\Http\Controllers\Api; // Emplacement du Controller API

use App\Http\Controllers\Controller; // Controller principal Laravel
use Illuminate\Http\Request; // Permet de récupérer les données de la requête
use App\Http\Requests\LoginRequest; // Validation de la connexion
use App\Http\Requests\RegisterRequest; // Validation de l'inscription
use App\Models\User; // Modèle utilisateur
use App\Services\EmailVerificationService; // Service de vérification email
use Illuminate\Support\Facades\Hash; // Gestion des mots de passe
use App\Services\LoginOtpService;


class AuthController extends Controller
{
    /**
     * Inscription d'un nouvel utilisateur.
     */
    public function register(
        RegisterRequest $request,
        EmailVerificationService $emailVerificationService
    ) {
        // Crée l'utilisateur
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'client',
        ]);

        // Génère et envoie le code de vérification
        $emailVerificationService->sendCode($user);

        // Retourne la réponse
        return response()->json([
            'message' => 'Utilisateur créé avec succès. Un code de vérification a été envoyé à votre adresse email.',
            'user' => $user,
        ], 201);
    }


    /**
     * Connexion d'un utilisateur.
     */
    public function login(LoginRequest $request, LoginOtpService $loginOtpService)
    {
        // Recherche l'utilisateur avec son email
        $user = User::where('email', $request->email)->first();

        // Vérifie les identifiants
        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Les identifiants sont incorrects',
            ], 401);
        }

        // Vérifie que l'adresse email a été confirmée
        if ($user->email_verified_at === null) {
            return response()->json([
                'message' => 'Votre adresse email n\'est pas encore vérifiée.',
            ], 403);
        }

        $loginOtpService->sendCode($user);

        return response()->json([
            'message' => 'Un code OTP a été envoyé à votre adresse email.',
        ]);
    }

    public function verifyLoginOtp(Request $request, LoginOtpService $loginOtpService)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'code' => ['required', 'digits:6'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !$loginOtpService->verifyCode($user, $request->code)) {
            return response()->json([
                'message' => 'Le code OTP est incorrect ou expiré.',
            ], 422);
        }

        $token = $user->createToken('shopmaster-token')->plainTextToken;

        return response()->json([
            'message' => 'Connexion réussie',
            'token' => $token,
            'user' => $user,
        ]);
    }

    public function resendLoginOtp(Request $request, LoginOtpService $loginOtpService)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || $user->email_verified_at === null) {
            return response()->json([
                'message' => 'Impossible de renvoyer le code OTP.',
            ], 422);
        }

        $loginOtpService->sendCode($user);

        return response()->json([
            'message' => 'Un nouveau code OTP a été envoyé à votre adresse email.',
        ]);
    }


    /**
     * Déconnexion de l'utilisateur.
     */
    public function logout(Request $request)
    {
        // Supprime uniquement le token actuellement utilisé
        $request->user()->currentAccessToken()->delete();

        // Retourne une réponse de confirmation
        return response()->json([
            'message' => 'Déconnexion réussie',
        ]);
    }
}
