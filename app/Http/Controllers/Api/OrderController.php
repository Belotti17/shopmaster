<?php

namespace App\Http\Controllers\Api; // Définit l'espace de noms du contrôleur

use App\Http\Controllers\Controller; // Importe le contrôleur principal de Laravel
use Illuminate\Http\Request; // Permet de récupérer les données envoyées par le client
use App\Models\Order; // Importe le modèle Order
use App\Models\Product; // Importe le modèle Product
use Illuminate\Support\Facades\DB; // Permet d'utiliser une transaction de base de données

class OrderController extends Controller
{
    // Crée une nouvelle commande pour le client connecté
    public function store(Request $request)
    {
        // Vérifie que la requête contient un tableau de produits
        $validated = $request->validate([
            'items' => 'required|array|min:1',

            // Vérifie chaque élément du tableau
            'items.*.product_id' => 'required|integer|exists:products,id',

            // Vérifie que la quantité est un entier positif
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        // Récupère l'utilisateur actuellement connecté
        $user = $request->user();

        // Commence une transaction pour garantir que toutes les opérations réussissent ensemble
        $order = DB::transaction(function () use ($validated, $user) {

            // Crée une nouvelle commande appartenant au client connecté
            $order = Order::create([
                'user_id' => $user->id,
                'total' => 0,
                'status' => 'pending',
            ]);

            // Initialise le montant total de la commande
            $total = 0;

            // Parcourt tous les produits envoyés dans la commande
            foreach ($validated['items'] as $item) {

                // Recherche le produit dans la base de données
                $product = Product::findOrFail($item['product_id']);

                // Récupère la quantité demandée
                $quantity = $item['quantity'];

                // Calcule le sous-total de cette ligne
                $subtotal = $product->price * $quantity;

                // Ajoute le sous-total au total général
                $total += $subtotal;

                // Crée la ligne de commande
                $order->items()->create([
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $product->price,
                ]);
            }

            // Met à jour le montant total de la commande
            $order->update([
                'total' => $total,
            ]);

            // Retourne la commande créée
            return $order;
        });

        // Recharge la commande avec son utilisateur et ses produits
        $order->load('user', 'items.product');

        // Retourne la réponse JSON
        return response()->json([
            'message' => 'Commande créée avec succès',
            'order' => $order,
        ], 201);
    }

    // Récupère les commandes du client connecté
    public function index(Request $request)
    {
        // Récupère uniquement les commandes appartenant au client connecté
        $orders = Order::where('user_id', $request->user()->id)
            ->with('items.product')
            ->latest()
            ->get();

        // Retourne les commandes au format JSON
        return response()->json([
            'message' => 'Liste des commandes récupérée avec succès',
            'orders' => $orders,
        ]);
    }

    // Récupère une commande précise du client connecté
    public function show(Request $request, Order $order)
    {
        // Vérifie que la commande appartient bien au client connecté
        if ($order->user_id !== $request->user()->id) {

            // Refuse l'accès à une commande appartenant à un autre utilisateur
            return response()->json([
                'message' => 'Accès refusé à cette commande.',
            ], 403);
        }

        // Charge les informations liées à la commande
        $order->load('user', 'items.product');

        // Retourne la commande au format JSON
        return response()->json([
            'message' => 'Commande récupérée avec succès',
            'order' => $order,
        ]);
    }
}