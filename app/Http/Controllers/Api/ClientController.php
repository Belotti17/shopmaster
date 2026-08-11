<?php // Indique que ce fichier contient du code PHP

namespace App\Http\Controllers\Api; // Définit l'espace de noms du contrôleur API

use App\Http\Controllers\Controller; // Importe le contrôleur principal de Laravel
use Illuminate\Http\Request; // Importe Request pour récupérer les informations de la requête HTTP
use App\Http\Requests\UpdateProfileRequest; // Importe la requête contenant les règles de validation du profil
use App\Http\Requests\UpdatePasswordRequest; // Importe la requête contenant les règles de validation du mot de passe
use Illuminate\Support\Facades\Hash; // Importe Hash pour vérifier et chiffrer les mots de passe


class ClientController extends Controller // Déclare le contrôleur destiné aux fonctionnalités du client
{
    public function profile(Request $request) // Déclare la méthode permettant de récupérer le profil du client connecté
    {
        return response()->json([ // Retourne une réponse au format JSON
            'message' => 'Bienvenue dans votre espace client', // Ajoute un message de bienvenue
            'user' => $request->user(), // Récupère l'utilisateur actuellement authentifié avec Sanctum
        ]);
    }


    public function updateProfile(UpdateProfileRequest $request) // Déclare la méthode permettant de modifier le profil du client
    {
    $user = $request->user(); // Récupère l'utilisateur actuellement authentifié

    $user->update([ // Met à jour les informations de l'utilisateur dans la base de données
        'name' => $request->name, // Met à jour le nom avec la valeur validée
        'email' => $request->email, // Met à jour l'email avec la valeur validée
    ]); // Termine la mise à jour de l'utilisateur

    return response()->json([ // Retourne une réponse au format JSON
        'message' => 'Profil modifié avec succès', // Ajoute un message de confirmation
        'user' => $user->fresh(), // Récupère les informations actualisées de l'utilisateur
    ]); // Termine la réponse JSON
    } // Termine la méthode updateProfile


    public function updatePassword(UpdatePasswordRequest $request) // Déclare la méthode permettant de modifier le mot de passe
    {
    $user = $request->user(); // Récupère l'utilisateur actuellement authentifié

    if (!Hash::check($request->current_password, $user->password)) { // Vérifie que l'ancien mot de passe correspond au mot de passe enregistré
        return response()->json([ // Retourne une réponse au format JSON
            'message' => 'L’ancien mot de passe est incorrect', // Informe le client que son ancien mot de passe est incorrect
        ], 422); // Retourne le code HTTP 422 indiquant une donnée invalide
    } // Termine la condition de vérification de l'ancien mot de passe

    $user->update([ // Met à jour le mot de passe de l'utilisateur
        'password' => Hash::make($request->password), // Chiffre le nouveau mot de passe avant de l'enregistrer
    ]); // Termine la mise à jour du mot de passe

    return response()->json([ // Retourne une réponse au format JSON
        'message' => 'Mot de passe modifié avec succès', // Confirme la modification du mot de passe
    ]); // Termine la réponse JSON
    } // Termine la méthode updatePassword
}