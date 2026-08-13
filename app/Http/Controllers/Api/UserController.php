<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    // Récupère la liste de tous les utilisateurs
    public function index()
    {
        // Récupère tous les utilisateurs dans la base de données
        $users = User::all();

        // Retourne une réponse au format JSON
        return response()->json([
            // Message de confirmation
            'message' => 'Liste des utilisateurs récupérée avec succès',

            // Envoie la liste des utilisateurs
            'users' => $users,
        ]);
    }

    // Récupère un utilisateur précis grâce à son identifiant
    public function show(User $user)
    {
        // Retourne une réponse au format JSON
        return response()->json([
            // Message de confirmation
            'message' => 'Utilisateur récupéré avec succès',

            // Retourne l'utilisateur demandé
            'user' => $user,
        ]);
    }

    // Récupère le profil de l'utilisateur actuellement connecté
    public function profile(Request $request)
    {
        // Retourne une réponse au format JSON
        return response()->json([
            // Message envoyé au client
            'message' => 'Profil récupéré avec succès',

            // Récupère l'utilisateur connecté grâce au token Sanctum
            'user' => $request->user(),
        ]);
    }
}