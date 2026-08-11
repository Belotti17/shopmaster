<?php // Indique que ce fichier contient du code PHP

namespace App\Http\Controllers\Api; // Indique que ce contrôleur appartient à l'espace de noms API

use App\Http\Controllers\Controller; // Importe le contrôleur principal de Laravel
use App\Models\Product; // Importe le modèle Product
use Illuminate\Http\Request; // Importe Request pour récupérer les données envoyées


class ProductController extends Controller // Déclare le contrôleur des produits
{
    public function index() // Déclare la méthode permettant de récupérer les produits
    {
        $products = Product::all(); // Récupère tous les produits présents dans la base de données

        return response()->json([ // Retourne une réponse au format JSON
            'message' => 'Liste des produits récupérée avec succès', // Ajoute un message de confirmation
            'products' => $products, // Ajoute la liste des produits à la réponse
        ]); // Termine la réponse JSON
    } // Termine la méthode index


    public function store(Request $request) // Déclare la méthode permettant de créer un nouveau produit
    {
        $product = Product::create([ // Crée un nouveau produit dans la base de données
            'name' => $request->name, // Récupère le nom envoyé dans la requête
            'description' => $request->description, // Récupère la description envoyée dans la requête
            'price' => $request->price, // Récupère le prix envoyé dans la requête
            'stock' => $request->stock, // Récupère le stock envoyé dans la requête
            'image' => $request->image, // Récupère le chemin de l'image envoyé dans la requête
        ]); // Termine la création du produit

        return response()->json([ // Retourne une réponse au format JSON
            'message' => 'Produit créé avec succès', // Ajoute un message de confirmation
            'product' => $product, // Retourne le produit qui vient d'être créé
        ], 201); // Retourne le code HTTP 201 indiquant une création réussie
    }


    public function show(Product $product) // Déclare la méthode permettant de récupérer un produit précis
    {
    return response()->json([ // Retourne une réponse au format JSON
        'message' => 'Produit récupéré avec succès', // Ajoute un message de confirmation
        'product' => $product, // Retourne le produit demandé
    ]); // Termine la réponse JSON
    } // Termine la méthode show


    public function update(Request $request, Product $product) // Déclare la méthode permettant de modifier un produit existant
    {
    $product->update([ // Met à jour le produit trouvé dans la base de données
        'name' => $request->name, // Met à jour le nom du produit
        'description' => $request->description, // Met à jour la description du produit
        'price' => $request->price, // Met à jour le prix du produit
        'stock' => $request->stock, // Met à jour le stock disponible
        'image' => $request->image, // Met à jour l'image du produit
    ]); // Termine la mise à jour du produit

    return response()->json([ // Retourne une réponse au format JSON
        'message' => 'Produit modifié avec succès', // Ajoute un message de confirmation
        'product' => $product->fresh(), // Récupère la version actualisée du produit
    ]); // Termine la réponse JSON
    } // Termine la méthode update
}