<?php // Indique que ce fichier contient du code PHP

namespace App\Http\Controllers\Api; // Indique que ce contrôleur appartient à l'espace de noms API

use App\Http\Controllers\Controller; // Importe le contrôleur principal de Laravel
use App\Http\Requests\ProductRequest; // Importe la requête contenant les règles de validation des produits
use App\Models\Product; // Importe le modèle Product


class ProductController extends Controller // Déclare le contrôleur des produits
{
    public function index() // Déclare la méthode permettant de récupérer tous les produits
    {
        $products = Product::all(); // Récupère tous les produits présents dans la base de données

        return response()->json([ // Retourne une réponse au format JSON
            'message' => 'Liste des produits récupérée avec succès', // Ajoute un message de confirmation
            'products' => $products, // Ajoute la liste des produits à la réponse
        ]); // Termine la réponse JSON
    } // Termine la méthode index


    public function store(ProductRequest $request) // Reçoit une requête validée pour créer un produit
    {
        $product = Product::create([ // Crée un nouveau produit dans la base de données
            'name' => $request->name, // Récupère le nom validé du produit
            'description' => $request->description, // Récupère la description validée du produit
            'price' => $request->price, // Récupère le prix validé du produit
            'stock' => $request->stock, // Récupère le stock validé du produit
            'image' => $request->image, // Récupère l'image validée du produit
        ]); // Termine la création du produit

        return response()->json([ // Retourne une réponse au format JSON
            'message' => 'Produit créé avec succès', // Ajoute un message de confirmation
            'product' => $product, // Retourne le produit créé
        ], 201); // Retourne le code HTTP 201 indiquant une création réussie
    } // Termine la méthode store


    public function show(Product $product) // Déclare la méthode permettant de récupérer un produit précis
    {
        return response()->json([ // Retourne une réponse au format JSON
            'message' => 'Produit récupéré avec succès', // Ajoute un message de confirmation
            'product' => $product, // Retourne le produit demandé
        ]); // Termine la réponse JSON
    } // Termine la méthode show


    public function update(ProductRequest $request, Product $product) // Reçoit une requête validée pour modifier un produit
    {
        $product->update([ // Met à jour le produit dans la base de données
            'name' => $request->name, // Met à jour le nom validé du produit
            'description' => $request->description, // Met à jour la description validée du produit
            'price' => $request->price, // Met à jour le prix validé du produit
            'stock' => $request->stock, // Met à jour le stock validé du produit
            'image' => $request->image, // Met à jour l'image validée du produit
        ]); // Termine la mise à jour du produit

        return response()->json([ // Retourne une réponse au format JSON
            'message' => 'Produit modifié avec succès', // Ajoute un message de confirmation
            'product' => $product->fresh(), // Récupère la version actualisée du produit
        ]); // Termine la réponse JSON
    } // Termine la méthode update


    public function destroy(Product $product) // Déclare la méthode permettant de supprimer un produit
    {
        $product->delete(); // Supprime le produit de la base de données

        return response()->json([ // Retourne une réponse au format JSON
            'message' => 'Produit supprimé avec succès', // Ajoute un message de confirmation
        ]); // Termine la réponse JSON
    } // Termine la méthode destroy
}