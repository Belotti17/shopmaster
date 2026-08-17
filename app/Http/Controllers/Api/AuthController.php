<?php

namespace App\Http\Controllers\Api; // Emplacement du Controller API

use App\Http\Controllers\Controller; // Controller principal Laravel
use Illuminate\Http\Request; // Importe Request pour récupérer les données de la requête
use App\Http\Requests\LoginRequest; // Validation de la connexion
use App\Http\Requests\RegisterRequest; // Validation de l'inscription
use App\Models\User; // Modèle utilisateur
use Illuminate\Support\Facades\Hash; // Gestion des mots de passe
use App\Services\EmailVerificationService; // Service de vérification de l'adresse email

class AuthController extends Controller // Controller pour gérer l'authentification
{
    public function register(
        RegisterRequest $request,
        EmailVerificationService $emailVerificationService
    ) // Fonction d'inscription
    {
        $user = User::create([ // Création de l'utilisateur
            'name' => $request->name, // Récupère le nom envoyé
            'email' => $request->email, // Récupère l'email envoyé
            'password' => Hash::make($request->password), // Hash le mot de passe
            'role' => 'client', // Donne automatiquement le rôle client
        ]);

        // Génère et envoie le code de vérification par email
        $emailVerificationService->sendCode($user);

        return response()->json([ // Retourne une réponse JSON
            'message' => 'Utilisateur créé avec succès. Un code de vérification a été envoyé à votre adresse email.', // Message de confirmation
            'user' => $user, // Retourne l'utilisateur créé
        ], 201); // Code HTTP 201 = création réussie
    }

    public function login(LoginRequest $request) // Fonction de connexion
    {
        $user = User::where('email', $request->email)->first(); // Recherche l'utilisateur par email

        if (!$user || !Hash::check($request->password, $user->password)) { // Vérifie les identifiants
            return response()->json([ // Retourne une erreur JSON
                'message' => 'Les identifiants sont incorrects', // Message d'erreur
            ], 401); // Code HTTP 401 = non authentifié
        }

        $token = $user->createToken('shopmaster-token')->plainTextToken; // Crée le token Sanctum

        return response()->json([ // Retourne la réponse de connexion
            'message' => 'Connexion réussie', // Message de confirmation
            'token' => $token, // Token pour les futures requêtes
            'user' => $user, // Informations de l'utilisateur connecté
        ]);
    }

    public function logout(Request $request) // Déclare la méthode permettant de déconnecter l'utilisateur
    {
        $request->user()->currentAccessToken()->delete(); // Supprime uniquement le token actuellement utilisé par l'utilisateur

        return response()->json([ // Retourne une réponse au format JSON
            'message' => 'Déconnexion réussie', // Message de confirmation
        ]);
    }
}