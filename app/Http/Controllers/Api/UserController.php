<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Requests\UserRequest; // Importe la requête contenant les règles de validation
use App\Http\Requests\PasswordRequest; // Importe la validation du nouveau mot de passe
use Illuminate\Support\Facades\Hash; // Permet de hasher le mot de passe

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
        // Récupère l'utilisateur actuellement connecté grâce au token Sanctum
        $currentUser = $request->user();

        // Vérifie si l'administrateur essaie de modifier son propre rôle
        if ($currentUser->id === $user->id && $request->role !== 'admin') {

            // Retourne une erreur et empêche la modification
            return response()->json([
                // Message expliquant pourquoi l'action est refusée
                'message' => 'Vous ne pouvez pas retirer votre propre rôle administrateur.',
            ], 403); // 403 = action interdite
        }

        // Met à jour les informations de l'utilisateur
        $user->update([
            'name' => $request->name, // Récupère le nouveau nom validé
            'email' => $request->email, // Récupère le nouvel email validé
            'role' => $request->role, // Récupère le nouveau rôle validé
        ]);

        // Retourne une réponse au format JSON
        return response()->json([
            // Message de confirmation
            'message' => 'Utilisateur modifié avec succès',

            // Retourne les informations actualisées de l'utilisateur
            'user' => $user->fresh(),
        ]);
    }


    // Modifie le mot de passe d'un utilisateur
    public function updatePassword(PasswordRequest $request, User $user)
    {
    // Hash le nouveau mot de passe avant de l'enregistrer
    $user->update([
        'password' => Hash::make($request->password),
    ]);

    // Retourne une réponse au format JSON
    return response()->json([
        // Message de confirmation
        'message' => 'Mot de passe modifié avec succès',
    ]);
    }

    // Supprime un utilisateur précis
    public function destroy(Request $request, User $user)
    {
        // Récupère l'utilisateur actuellement connecté grâce à Sanctum
        $currentUser = $request->user();

        // Vérifie si l'administrateur essaie de supprimer son propre compte
        if ($currentUser->id === $user->id) {

            // Retourne une erreur et empêche la suppression
            return response()->json([
                // Message expliquant pourquoi la suppression est refusée
                'message' => 'Vous ne pouvez pas supprimer votre propre compte.',
            ], 403); // 403 = action interdite
        }

        // Supprime l'utilisateur demandé de la base de données
        $user->delete();

        // Retourne une réponse au format JSON
        return response()->json([
            // Message de confirmation
            'message' => 'Utilisateur supprimé avec succès',
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