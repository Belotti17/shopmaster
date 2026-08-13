<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserRequest; // Importe la requête contenant les règles de validation

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

    // Modifie les informations d'un utilisateur
    public function update(UserRequest $request, User $user)
    {
        // Met à jour les informations de l'utilisateur
        $user->update([
            'name' => $request->name, // Récupère le nouveau nom validé
            'email' => $request->email, // Récupère le nouvel email validé
            'role' => $request->role, // Récupère le nouveau rôle validé
        ]);

        // Retourne une réponse JSON
        return response()->json([
            // Message de confirmation
            'message' => 'Utilisateur modifié avec succès',

            // Retourne les informations actualisées de l'utilisateur
            'user' => $user->fresh(),
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