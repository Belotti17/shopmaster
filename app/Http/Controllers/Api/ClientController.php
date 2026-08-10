<?php // Indique que ce fichier contient du code PHP

namespace App\Http\Controllers\Api; // Définit l'espace de noms du contrôleur API

use App\Http\Controllers\Controller; // Importe le contrôleur principal de Laravel
use Illuminate\Http\Request; // Importe Request pour récupérer les informations de la requête HTTP

class ClientController extends Controller // Déclare le contrôleur destiné aux fonctionnalités du client
{
    public function profile(Request $request) // Déclare la méthode permettant de récupérer le profil du client connecté
    {
        return response()->json([ // Retourne une réponse au format JSON
            'message' => 'Bienvenue dans votre espace client', // Ajoute un message de bienvenue
            'user' => $request->user(), // Récupère l'utilisateur actuellement authentifié avec Sanctum
        ]);
    }
}