<?php // Indique que ce fichier contient du code PHP

use App\Http\Controllers\Api\AuthController; // Importe le contrôleur d'authentification
use App\Http\Controllers\Api\ClientController; // Importe le contrôleur des fonctionnalités client
use App\Http\Controllers\Api\ProductController; // Importe le contrôleur des produits
use Illuminate\Http\Request; // Importe Request pour récupérer les informations de la requête
use Illuminate\Support\Facades\Route; // Importe le système de gestion des routes Laravel


Route::post('/register', [AuthController::class, 'register']); // Crée la route POST pour l'inscription d'un nouvel utilisateur

Route::post('/login', [AuthController::class, 'login']); // Crée la route POST pour connecter un utilisateur

Route::get('/user', function (Request $request) { // Crée la route GET permettant de récupérer l'utilisateur connecté
    return $request->user(); // Retourne les informations de l'utilisateur authentifié
})->middleware('auth:sanctum'); // Protège la route avec l'authentification Sanctum

Route::get('/client/profile', [ClientController::class, 'profile']) // Crée la route GET permettant au client de consulter son profil
    ->middleware('auth:sanctum'); // Vérifie que l'utilisateur est authentifié avec Sanctum

Route::get('/products', [ProductController::class, 'index']); // Crée la route GET pour récupérer la liste des produits
Route::get('/products/{product}', [ProductController::class, 'show']); // Récupère un produit précis grâce à son identifiant
Route::put('/products/{product}', [ProductController::class, 'update']) // Crée la route PUT pour modifier un produit
    ->middleware(['auth:sanctum', 'admin']); // Autorise uniquement un administrateur authentifié
Route::post('/products', [ProductController::class, 'store']) // Crée un nouveau produit
    ->middleware(['auth:sanctum', 'admin']); // Autorise uniquement un administrateur authentifié
Route::get('/admin-test', function (Request $request) { // Crée temporairement une route de test pour l'administrateur
    return response()->json([ // Retourne une réponse au format JSON
        'message' => 'Bienvenue dans la zone administrateur',
        'user' => $request->user(), // Récupère les informations de l'administrateur connecté
    ]);
})->middleware(['auth:sanctum', 'admin']); // Vérifie à la fois l'authentification et le rôle administrateur