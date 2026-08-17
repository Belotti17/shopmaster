<?php

namespace App\Http\Controllers\Api; // Emplacement du Controller API

use App\Http\Controllers\Controller; // Controller principal Laravel
use Illuminate\Http\Request; // Permet de récupérer les données de la requête
use App\Http\Requests\LoginRequest; // Validation de la connexion
use App\Http\Requests\RegisterRequest; // Validation de l'inscription
use App\Models\User; // Modèle utilisateur
use App\Services\EmailVerificationService; // Service de vérification email
use Illuminate\Support\Facades\Hash; // Gestion des mots de passe


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
    public function login(LoginRequest $request)
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

        // Crée le token Sanctum
        $token = $user->createToken('shopmaster-token')->plainTextToken;

        // Retourne la réponse de connexion
        return response()->json([
            'message' => 'Connexion réussie',
            'token' => $token,
            'user' => $user,
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