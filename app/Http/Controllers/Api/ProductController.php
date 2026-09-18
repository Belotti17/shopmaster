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
    public function index(Request $request) // Déclare la méthode permettant de récupérer tous les produits
    {
        $search = trim((string) $request->input('search', ''));
        $categoryId = $request->integer('category_id');
        $minPrice = $request->input('min_price');
        $maxPrice = $request->input('max_price');
        $inStockOnly = $request->boolean('in_stock');
        $sort = $request->input('sort', 'newest');
        $perPage = min(max((int) $request->input('per_page', 12), 1), 100);

        $cacheKey = 'products_index_' . md5(json_encode([
            $search,
            $categoryId,
            $minPrice,
            $maxPrice,
            $inStockOnly,
            $sort,
            $perPage,
        ]));

        $products = Cache::remember($cacheKey, 300, function () use ($search, $categoryId, $minPrice, $maxPrice, $inStockOnly, $sort, $perPage) {
            $query = Product::query()->with('category');

            if ($search !== '') {
                $query->where(function ($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                        ->orWhere('description', 'like', "%{$search}%");
                });
            }

            if ($categoryId) {
                $query->where('category_id', $categoryId);
            }

            if ($minPrice !== null && $minPrice !== '') {
                $query->where('price', '>=', (float) $minPrice);
            }

            if ($maxPrice !== null && $maxPrice !== '') {
                $query->where('price', '<=', (float) $maxPrice);
            }

            if ($inStockOnly) {
                $query->where('stock', '>', 0);
            }

            match ($sort) {
                'price_asc' => $query->orderBy('price', 'asc'),
                'price_desc' => $query->orderBy('price', 'desc'),
                'name_asc' => $query->orderBy('name', 'asc'),
                'name_desc' => $query->orderBy('name', 'desc'),
                'oldest' => $query->orderBy('created_at', 'asc'),
                default => $query->orderBy('created_at', 'desc'),
            };

            $result = $query->paginate($perPage);

            return $result;
        });

        return response()->json([
            'message' => 'Liste des produits récupérée avec succès',
            'products' => $products->items(),
            'pagination' => [
                'current_page' => $products->currentPage(),
                'per_page' => $products->perPage(),
                'total' => $products->total(),
                'last_page' => $products->lastPage(),
            ],
        ]);
    } // Termine la méthode index


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

        Cache::forget('products_index_');
        Log::info('Produit créé', ['product_id' => $product->id, 'name' => $product->name]);

        return response()->json([ // Retourne une réponse au format JSON
            'message' => 'Produit créé avec succès', // Ajoute un message de confirmation
            'product' => $product->load('category'), // Retourne le produit avec sa catégorie
        ], 201); // Retourne le code HTTP 201 indiquant une création réussie
    } // Termine la méthode store


    public function show(Product $product) // Déclare la méthode permettant de récupérer un produit précis
    {
        $product->load('category'); // Charge la catégorie associée au produit

        return response()->json([ // Retourne une réponse au format JSON
            'message' => 'Produit récupéré avec succès', // Ajoute un message de confirmation
            'product' => $product, // Retourne le produit avec sa catégorie
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
            'category_id' => $request->category_id, // Met à jour la catégorie du produit
        ]);

        Cache::forget('products_index_');
        Log::info('Produit mis à jour', ['product_id' => $product->id, 'name' => $product->name]);

        return response()->json([ // Retourne une réponse au format JSON
            'message' => 'Produit modifié avec succès', // Ajoute un message de confirmation
            'product' => $product->fresh()->load('category'), // Retourne la version actualisée avec sa catégorie
        ]); // Termine la réponse JSON
    } // Termine la méthode update


    public function destroy(Product $product) // Déclare la méthode permettant de supprimer un produit
    {
        $product->delete(); // Supprime le produit de la base de données

        Cache::forget('products_index_');
        Log::warning('Produit supprimé', ['product_id' => $product->id, 'name' => $product->name]);

        return response()->json([ // Retourne une réponse au format JSON
            'message' => 'Produit supprimé avec succès', // Ajoute un message de confirmation
        ]); // Termine la réponse JSON
    } // Termine la méthode destroy
}
