<?php // Indique que ce fichier contient du code PHP

namespace App\Http\Controllers\Api; // Définit l'espace de noms du contrôleur API

use App\Http\Controllers\Controller; // Importe le contrôleur principal de Laravel
use Illuminate\Http\Request; // Importe Request pour récupérer les informations de la requête HTTP
use App\Http\Requests\UpdateProfileRequest; // Importe la requête contenant les règles de validation du profil

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
}