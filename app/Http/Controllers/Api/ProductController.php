<?php // Indique que ce fichier contient du code PHP

namespace App\Http\Controllers\Api; // Indique que ce contrôleur appartient à l'espace de noms API

use App\Http\Controllers\Controller; // Importe le contrôleur principal de Laravel
use App\Models\Product; // Importe le modèle Product pour communiquer avec la table products

class ProductController extends Controller // Déclare le contrôleur des produits
{
    public function index() // Déclare la méthode permettant de récupérer les produits
    {
        $products = Product::all(); // Récupère tous les produits présents dans la base de données

        return response()->json([ // Retourne une réponse au format JSON
            'message' => 'Liste des produits récupérée avec succès', // Ajoute un message de confirmation
            'products' => $products, // Ajoute la liste des produits dans la réponse
        ]); // Termine la réponse JSON
    }
}