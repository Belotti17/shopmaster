<?php // Indique que ce fichier contient du code PHP

namespace App\Http\Controllers\Api; // Indique que ce contrôleur appartient à l'espace de noms API

use App\Http\Controllers\Controller; // Importe le contrôleur principal de Laravel
use App\Http\Requests\ProductRequest; // Importe la requête contenant les règles de validation des produits
use App\Models\Product; // Importe le modèle Product
use Illuminate\Http\Request; // Permet d'accéder aux paramètres de requête
use Illuminate\Support\Facades\Cache; // Permet d'utiliser le cache Laravel
use Illuminate\Support\Facades\Log; // Permet d'écrire des logs

class ProductController extends Controller // Déclare le contrôleur des produits
{
    public function index(Request $request) // Déclare la méthode permettant de récupérer les produits
    {
        $search = trim((string) $request->input('search', '')); // Récupère le texte de recherche
        $categoryId = $request->integer('category_id'); // Récupère l'identifiant de la catégorie
        $minPrice = $request->input('min_price'); // Récupère le prix minimum
        $maxPrice = $request->input('max_price'); // Récupère le prix maximum
        $inStockOnly = $request->boolean('in_stock'); // Vérifie si seuls les produits en stock sont demandés

        $sort = $request->input('sort', 'newest'); // Récupère le type de tri
        $direction = $request->input('direction', 'desc'); // Récupère le sens du tri

        $perPage = min(max((int) $request->input('per_page', 12), 1), 100); // Limite le nombre de produits par page

        $page = $request->input('page', 1); // Récupère le numéro de la page

        $cacheVersion = Cache::get('products_cache_version', 1); // Récupère la version actuelle du cache

        $cacheKey = 'products_index_v' . $cacheVersion . '_' . md5(json_encode([ // Construit une clé unique pour cette liste
            $search, // Ajoute la recherche dans la clé
            $categoryId, // Ajoute la catégorie dans la clé
            $minPrice, // Ajoute le prix minimum dans la clé
            $maxPrice, // Ajoute le prix maximum dans la clé
            $inStockOnly, // Ajoute le filtre de stock dans la clé
            $sort, // Ajoute le type de tri dans la clé
            $direction, // Ajoute la direction du tri dans la clé
            $perPage, // Ajoute le nombre de produits par page dans la clé
            $page, // Ajoute la page dans la clé
        ]));

        $data = Cache::remember($cacheKey, 300, function () use ( // Cherche la liste dans le cache pendant 5 minutes
            $search, // Transmet la recherche
            $categoryId, // Transmet la catégorie
            $minPrice, // Transmet le prix minimum
            $maxPrice, // Transmet le prix maximum
            $inStockOnly, // Transmet le filtre de stock
            $sort, // Transmet le tri
            $direction, // Transmet la direction
            $perPage // Transmet le nombre de produits par page
        ) {
            $query = Product::query()->with('category'); // Prépare la requête avec la catégorie associée

            if ($search !== '') { // Vérifie si une recherche est demandée
                $query->where(function ($q) use ($search) { // Recherche dans le nom ou la description
                    $q->where('name', 'like', "%{$search}%") // Recherche dans le nom
                        ->orWhere('description', 'like', "%{$search}%"); // Recherche dans la description
                });
            }

            if ($categoryId) { // Vérifie si une catégorie est demandée
                $query->where('category_id', $categoryId); // Filtre par catégorie
            }

            if ($minPrice !== null && $minPrice !== '') { // Vérifie si un prix minimum existe
                $query->where('price', '>=', (float) $minPrice); // Applique le prix minimum
            }

            if ($maxPrice !== null && $maxPrice !== '') { // Vérifie si un prix maximum existe
                $query->where('price', '<=', (float) $maxPrice); // Applique le prix maximum
            }

            if ($inStockOnly) { // Vérifie si le filtre de stock est activé
                $query->where('stock', '>', 0); // Garde uniquement les produits disponibles
            }

            if ($sort === 'price') { // Vérifie si le tri demandé concerne le prix
                $query->orderBy('price', $direction === 'asc' ? 'asc' : 'desc'); // Trie le prix
            } elseif ($sort === 'name') { // Vérifie si le tri demandé concerne le nom
                $query->orderBy('name', $direction === 'asc' ? 'asc' : 'desc'); // Trie le nom
            } elseif ($sort === 'oldest') { // Vérifie si les anciens produits sont demandés
                $query->orderBy('created_at', 'asc'); // Trie du plus ancien au plus récent
            } else { // Utilise le tri par défaut
                $query->orderBy('created_at', 'desc'); // Trie du plus récent au plus ancien
            }

            $result = $query->paginate($perPage); // Exécute la requête avec pagination

            return [ // Retourne uniquement des données simples dans le cache
                'products' => $result->items(), // Récupère les produits de la page actuelle
                'pagination' => [ // Prépare les informations de pagination
                    'current_page' => $result->currentPage(), // Retourne la page actuelle
                    'per_page' => $result->perPage(), // Retourne le nombre de produits par page
                    'total' => $result->total(), // Retourne le nombre total de produits
                    'last_page' => $result->lastPage(), // Retourne le nombre total de pages
                ],
            ];
        });

        return response()->json([ // Retourne la réponse au format JSON
            'message' => 'Liste des produits récupérée avec succès', // Ajoute le message de confirmation
            'products' => $data['products'], // Retourne les produits
            'pagination' => $data['pagination'], // Retourne les informations de pagination
        ]);
    }

    private function invalidateProductsCache(): void // Déclare une méthode pour invalider le cache des produits
    {
        Cache::increment('products_cache_version'); // Augmente la version du cache des produits
    }

    public function store(ProductRequest $request) // Reçoit une requête validée pour créer un produit
    {
        $product = Product::create([ // Crée un nouveau produit dans la base de données
            'name' => $request->name, // Récupère le nom validé du produit
            'description' => $request->description, // Récupère la description validée du produit
            'price' => $request->price, // Récupère le prix validé du produit
            'stock' => $request->stock, // Récupère le stock validé du produit
            'image' => $request->image, // Récupère l'image validée du produit
            'category_id' => $request->category_id, // Associe le produit à sa catégorie
        ]);

        $this->invalidateProductsCache(); // Invalide les anciennes listes de produits

        Log::info('Produit créé', ['product_id' => $product->id, 'name' => $product->name]); // Enregistre la création dans les logs

        return response()->json([ // Retourne une réponse au format JSON
            'message' => 'Produit créé avec succès', // Ajoute un message de confirmation
            'product' => $product->load('category'), // Retourne le produit avec sa catégorie
        ], 201); // Retourne le code HTTP 201 indiquant une création réussie
    }

    public function show(Product $product) // Déclare la méthode permettant de récupérer un produit précis
    {
        $product->load('category'); // Charge la catégorie associée au produit

        return response()->json([ // Retourne une réponse au format JSON
            'message' => 'Produit récupéré avec succès', // Ajoute un message de confirmation
            'product' => $product, // Retourne le produit avec sa catégorie
        ]);
    }

    public function update(ProductRequest $request, Product $product) // Reçoit une requête validée pour modifier un produit
    {
        $product->update([ // Met à jour le produit dans la base de données
            'name' => $request->name, // Met à jour le nom validé du produit
            'description' => $request->description, // Met à jour la description validée du produit
            'price' => $request->price, // Met à jour le prix validé du produit
            'stock' => $request->stock, // Met à jour le stock validé du produit
            'image' => $request->image, // Met à jour l'image validée du produit
            'category_id' => $request->category_id, // Met à jour la catégorie du produit
        ]);

        $this->invalidateProductsCache(); // Invalide les anciennes listes de produits

        Log::info('Produit mis à jour', ['product_id' => $product->id, 'name' => $product->name]); // Enregistre la modification dans les logs

        return response()->json([ // Retourne une réponse au format JSON
            'message' => 'Produit modifié avec succès', // Ajoute un message de confirmation
            'product' => $product->fresh()->load('category'), // Retourne la version actualisée avec sa catégorie
        ]);
    }

    public function destroy(Product $product) // Déclare la méthode permettant de supprimer un produit
    {
        $product->delete(); // Supprime le produit de la base de données

        $this->invalidateProductsCache(); // Invalide les anciennes listes de produits

        Log::warning('Produit supprimé', ['product_id' => $product->id, 'name' => $product->name]); // Enregistre la suppression dans les logs

        return response()->json([ // Retourne une réponse au format JSON
            'message' => 'Produit supprimé avec succès', // Ajoute un message de confirmation
        ]);
    }
}
