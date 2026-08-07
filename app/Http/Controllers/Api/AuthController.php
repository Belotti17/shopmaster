<?php

namespace App\Http\Controllers\Api; // Emplacement du Controller API

use App\Http\Controllers\Controller; // Controller principal Laravel
use App\Http\Requests\LoginRequest; // Validation de la connexion
use App\Http\Requests\RegisterRequest; // Validation de l'inscription
use App\Models\User; // Modèle utilisateur
use Illuminate\Support\Facades\Hash; // Gestion des mots de passe


class AuthController extends Controller // Controller pour gérer l'authentification
{
    public function register(RegisterRequest $request) // Fonction d'inscription utilisateur
    {
        $user = User::create([ // Création de l'utilisateur en base de données
            'name' => $request->name, // Récupère le nom envoyé
            'email' => $request->email, // Récupère l'email envoyé
            'password' => Hash::make($request->password), // Hash le mot de passe
        ]);

        return response()->json([ // Retourne une réponse JSON
            'message' => 'Utilisateur créé avec succès', // Message de confirmation
            'user' => $user, // Retourne les informations utilisateur
        ], 201); // Code HTTP 201 = création réussie
    }


    public function login(LoginRequest $request) // Fonction de connexion utilisateur
    {
        $user = User::where('email', $request->email)->first(); // Recherche l'utilisateur avec son email

        if (!$user || !Hash::check($request->password, $user->password)) { // Vérifie l'utilisateur et son mot de passe
            return response()->json([ // Retourne une erreur JSON
                'message' => 'Les identifiants sont incorrects', // Message d'erreur
            ], 401); // Code HTTP 401 = non authentifié
        }

        $token = $user->createToken('shopmaster-token')->plainTextToken; // Crée un token Sanctum pour l'utilisateur

        return response()->json([ // Retourne la réponse de connexion
            'message' => 'Connexion réussie', // Message de confirmation
            'token' => $token, // Token utilisé pour les futures requêtes
            'user' => $user, // Informations de l'utilisateur connecté
        ]);
    }
}
