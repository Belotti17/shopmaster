<?php

namespace App\Http\Controllers\Api; // Définit l'espace de noms du contrôleur

use App\Http\Controllers\Controller; // Importe le contrôleur principal de Laravel
use Illuminate\Http\Request; // Permet de récupérer les données envoyées par le client
use App\Models\Order; // Importe le modèle Order
use App\Models\Product; // Importe le modèle Product
use Illuminate\Support\Facades\DB; // Permet d'utiliser les transactions de base de données

class OrderController extends Controller
{
    // Crée une nouvelle commande pour le client connecté
    public function store(Request $request)
    {
        // Valide les données envoyées par le client
        $validated = $request->validate([
            'items' => 'required|array|min:1', // Vérifie que la commande contient au moins un produit

            'items.*.product_id' => 'required|integer|exists:products,id', // Vérifie que chaque produit existe

            'items.*.quantity' => 'required|integer|min:1', // Vérifie que chaque quantité est positive
        ]);

        // Récupère l'utilisateur actuellement connecté
        $user = $request->user();

        // Lance une transaction afin de garantir la cohérence des opérations
        $order = DB::transaction(function () use ($validated, $user) {

            // Crée une nouvelle commande appartenant à l'utilisateur connecté
            $order = Order::create([
                'user_id' => $user->id, // Associe la commande au client
                'total' => 0, // Le total sera calculé plus bas
                'status' => 'pending', // Définit la commande comme étant en attente
            ]);

            // Initialise le total de la commande
            $total = 0;

            // Parcourt chaque produit envoyé dans la commande
            foreach ($validated['items'] as $item) {

                // Récupère le produit dans la base de données
                $product = Product::findOrFail($item['product_id']);

                // Récupère la quantité demandée
                $quantity = $item['quantity'];

                // Vérifie que le stock disponible est suffisant
                if ($quantity > $product->stock) {

                    // Arrête la transaction avec une exception
                    throw new \Exception(
                        "Stock insuffisant pour le produit : {$product->name}"
                    );
                }

                // Calcule le prix total de cette ligne
                $subtotal = $product->price * $quantity;

                // Ajoute le prix de cette ligne au total général
                $total += $subtotal;

                // Crée la ligne de commande
                $order->items()->create([
                    'product_id' => $product->id, // Enregistre le produit commandé
                    'quantity' => $quantity, // Enregistre la quantité commandée
                    'price' => $product->price, // Enregistre le prix au moment de la commande
                ]);

                // Diminue le stock du produit
                $product->decrement('stock', $quantity);
            }

            // Met à jour le total de la commande
            $order->update([
                'total' => $total,
            ]);

            // Retourne la commande créée
            return $order;
        });

        // Charge les relations nécessaires pour la réponse
        $order->load('user', 'items.product');

        // Retourne la commande créée au client
        return response()->json([
            'message' => 'Commande créée avec succès',
            'order' => $order,
        ], 201);
    }

    // Récupère toutes les commandes du client connecté
    public function index(Request $request)
    {
        // Récupère uniquement les commandes appartenant au client connecté
        $orders = Order::where('user_id', $request->user()->id)
            ->with('items.product') // Charge les produits des commandes
            ->latest() // Trie les commandes de la plus récente à la plus ancienne
            ->get(); // Exécute la requête

        // Retourne la liste des commandes
        return response()->json([
            'message' => 'Liste des commandes récupérée avec succès',
            'orders' => $orders,
        ]);
    }

    // Récupère une commande précise
    public function show(Request $request, Order $order)
    {
        // Vérifie que la commande appartient au client connecté
        if ($order->user_id !== $request->user()->id) {

            // Refuse l'accès si la commande appartient à quelqu'un d'autre
            return response()->json([
                'message' => 'Accès refusé à cette commande.',
            ], 403);
        }

        // Charge les informations de la commande
        $order->load('user', 'items.product');

        // Retourne la commande
        return response()->json([
            'message' => 'Commande récupérée avec succès',
            'order' => $order,
        ]);
    }
}