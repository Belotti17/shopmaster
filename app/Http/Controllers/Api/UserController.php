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
        // Récupère tous les utilisateurs présents dans la base de données
        $users = User::all();

        // Retourne une réponse au format JSON
        return response()->json([
            // Message envoyé au client
            'message' => 'Liste des utilisateurs récupérée avec succès',

            // Ajoute la liste des utilisateurs dans la réponse
            'users' => $users,
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