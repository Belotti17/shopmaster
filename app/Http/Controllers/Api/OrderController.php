<?php

namespace App\Http\Controllers\Api; // Définit l'espace de noms du contrôleur API

use App\Http\Controllers\Controller; // Importe le contrôleur principal de Laravel
use Illuminate\Http\Request; // Permet de récupérer les données envoyées dans la requête
use App\Models\Order; // Importe le modèle Order
use App\Models\Product; // Importe le modèle Product
use Illuminate\Support\Facades\DB; // Permet d'utiliser les transactions de base de données
use Illuminate\Http\Exceptions\HttpResponseException; // Permet de retourner une erreur HTTP personnalisée

class OrderController extends Controller // Déclare le contrôleur des commandes
{
    // Crée une nouvelle commande pour le client connecté
    public function store(Request $request)
    {
        // Valide les informations envoyées par le client
        $validated = $request->validate([
            'items' => 'required|array|min:1', // Vérifie que la commande contient au moins un article
            'items.*.product_id' => 'required|integer|exists:products,id', // Vérifie que chaque produit existe
            'items.*.quantity' => 'required|integer|min:1', // Vérifie que chaque quantité est un entier positif
        ]);

        // Récupère le client actuellement connecté
        $user = $request->user();

        // Lance une transaction pour garantir la cohérence de la commande
        $order = DB::transaction(function () use ($validated, $user) {

            // Crée une nouvelle commande
            $order = Order::create([
                'user_id' => $user->id, // Associe la commande au client connecté
                'total' => 0, // Initialise le total à zéro
                'status' => 'pending', // Définit la commande comme étant en attente
            ]);

            // Initialise le montant total de la commande
            $total = 0;

            // Parcourt tous les produits envoyés dans la commande
            foreach ($validated['items'] as $item) {

                // Recherche le produit correspondant dans la base de données
                $product = Product::findOrFail($item['product_id']);

                // Récupère la quantité demandée par le client
                $quantity = $item['quantity'];

                // Vérifie que le stock disponible est suffisant
                if ($quantity > $product->stock) {

                    // Retourne une erreur HTTP propre au lieu d'afficher une trace Laravel
                    throw new HttpResponseException(
                        response()->json([
                            'message' => "Stock insuffisant pour le produit : {$product->name}", // Message d'erreur
                            'stock_disponible' => $product->stock, // Indique le stock disponible
                            'quantite_demandee' => $quantity, // Indique la quantité demandée
                        ], 422) // Code HTTP indiquant que les données ne peuvent pas être traitées
                    );
                }

                // Calcule le sous-total de cet article
                $subtotal = $product->price * $quantity;

                // Ajoute le sous-total au total général de la commande
                $total += $subtotal;

                // Crée l'article correspondant dans la table order_items
                $order->items()->create([
                    'product_id' => $product->id, // Enregistre l'identifiant du produit
                    'quantity' => $quantity, // Enregistre la quantité commandée
                    'price' => $product->price, // Enregistre le prix du produit au moment de la commande
                ]);

                // Diminue le stock du produit selon la quantité commandée
                $product->decrement('stock', $quantity);
            }

            // Met à jour le montant total de la commande
            $order->update([
                'total' => $total, // Enregistre le total calculé
            ]);

            // Retourne la commande créée
            return $order;
        });

        // Charge les informations du client et des produits commandés
        $order->load('user', 'items.product');

        // Retourne la commande créée au format JSON
        return response()->json([
            'message' => 'Commande créée avec succès', // Message de confirmation
            'order' => $order, // Retourne les informations de la commande
        ], 201); // Code HTTP 201 indiquant une création réussie
    }

    // Récupère les commandes du client connecté
    public function index(Request $request)
    {
        // Récupère uniquement les commandes appartenant au client connecté
        $orders = Order::where('user_id', $request->user()->id)
            ->with('items.product') // Charge les articles et leurs produits
            ->latest() // Place les commandes les plus récentes en premier
            ->get(); // Exécute la requête

        // Retourne la liste des commandes
        return response()->json([
            'message' => 'Liste des commandes récupérée avec succès', // Message de confirmation
            'orders' => $orders, // Retourne les commandes
        ]);
    }

    // Récupère une commande précise
    public function show(Request $request, Order $order)
    {
        // Vérifie que la commande appartient bien au client connecté
        if ($order->user_id !== $request->user()->id) {

            // Refuse l'accès à une commande appartenant à un autre client
            return response()->json([
                'message' => 'Accès refusé à cette commande.', // Message d'erreur
            ], 403); // Code HTTP 403 indiquant que l'accès est interdit
        }

        // Charge le client, les articles et les produits de la commande
        $order->load('user', 'items.product');

        // Retourne la commande demandée
        return response()->json([
            'message' => 'Commande récupérée avec succès', // Message de confirmation
            'order' => $order, // Retourne la commande
        ]);
    }
}