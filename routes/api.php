<?php // Indique que ce fichier contient du code PHP

use App\Http\Controllers\Api\AuthController; // Importe le contrôleur d'authentification
use App\Http\Controllers\Api\ClientController; // Importe le contrôleur des fonctionnalités du profil
use App\Http\Controllers\Api\ProductController; // Importe le contrôleur des produits
use Illuminate\Support\Facades\Route; // Importe le système de gestion des routes Laravel
use App\Http\Controllers\Api\CategoryController; // Importe le contrôleur des catégories
use App\Http\Controllers\Api\UserController; // Importe le contrôleur des utilisateurs
use App\Http\Controllers\Api\OrderController; // Importe le contrôleur des commandes
use App\Http\Controllers\Api\VerificationController; // Importe le contrôleur de vérification d'email


// ======================================================
// AUTHENTIFICATION
// ======================================================

// Crée la route POST pour l'inscription d'un nouvel utilisateur
Route::post('/register', [AuthController::class, 'register']);

// Crée la route POST pour connecter un utilisateur
Route::post('/login', [AuthController::class, 'login']);

// Crée la route POST permettant à l'utilisateur de se déconnecter
Route::post('/logout', [AuthController::class, 'logout'])
    ->middleware('auth:sanctum'); // Vérifie que l'utilisateur est authentifié avant de le déconnecter


// ======================================================
// PROFIL DE L'UTILISATEUR CONNECTÉ
// ======================================================

// Crée la route GET permettant de récupérer l'utilisateur connecté
Route::get('/user', function () {
    return request()->user(); // Retourne les informations de l'utilisateur authentifié
})->middleware('auth:sanctum'); // Protège la route avec Sanctum


// Route permettant à l'utilisateur connecté de consulter son profil
Route::middleware('auth:sanctum')->get('/profile', [UserController::class, 'profile']);


// Route permettant à l'utilisateur connecté de modifier son profil
Route::put('/profile', [ClientController::class, 'updateProfile'])
    ->middleware('auth:sanctum'); // Vérifie que l'utilisateur est authentifié


// Route permettant à l'utilisateur connecté de modifier son propre mot de passe
Route::put('/profile/password', [ClientController::class, 'updatePassword'])
    ->middleware('auth:sanctum'); // Vérifie que l'utilisateur est authentifié


// ======================================================
// PRODUITS
// ======================================================

// Crée la route GET pour récupérer la liste des produits
Route::get('/products', [ProductController::class, 'index']);

// Crée la route GET pour récupérer un produit précis
Route::get('/products/{product}', [ProductController::class, 'show']);

// Crée la route POST pour créer un produit
Route::post('/products', [ProductController::class, 'store'])
    ->middleware(['auth:sanctum', 'admin']); // Autorise uniquement un administrateur authentifié

// Crée la route PUT pour modifier un produit
Route::put('/products/{product}', [ProductController::class, 'update'])
    ->middleware(['auth:sanctum', 'admin']); // Autorise uniquement un administrateur authentifié

// Crée la route DELETE pour supprimer un produit
Route::delete('/products/{product}', [ProductController::class, 'destroy'])
    ->middleware(['auth:sanctum', 'admin']); // Autorise uniquement un administrateur authentifié


// ======================================================
// CATÉGORIES
// ======================================================

// Routes publiques permettant de consulter les catégories

// Récupère toutes les catégories
Route::get('/categories', [CategoryController::class, 'index']);

// Récupère une catégorie précise
Route::get('/categories/{category}', [CategoryController::class, 'show']);


// Routes protégées réservées à l'administrateur

// Crée une nouvelle catégorie
Route::post('/categories', [CategoryController::class, 'store'])
    ->middleware(['auth:sanctum', 'admin']); // Vérifie que l'utilisateur est connecté et administrateur

// Modifie une catégorie
Route::put('/categories/{category}', [CategoryController::class, 'update'])
    ->middleware(['auth:sanctum', 'admin']); // Vérifie que l'utilisateur est connecté et administrateur

// Supprime une catégorie
Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])
    ->middleware(['auth:sanctum', 'admin']); // Vérifie que l'utilisateur est connecté et administrateur


// ======================================================
// GESTION DES UTILISATEURS PAR L'ADMINISTRATEUR
// ======================================================

// Route permettant à l'administrateur de consulter tous les utilisateurs
Route::get('/users', [UserController::class, 'index'])
    ->middleware(['auth:sanctum', 'admin']); // Seul un administrateur authentifié peut accéder à cette route


// Récupère un utilisateur précis
Route::get('/users/{user}', [UserController::class, 'show'])
    ->middleware(['auth:sanctum', 'admin']); // Seul un administrateur authentifié peut accéder à cette route


// Modifie les informations d'un utilisateur
Route::put('/users/{user}', [UserController::class, 'update'])
    ->middleware(['auth:sanctum', 'admin']); // Seul un administrateur authentifié peut modifier un utilisateur


// Supprime un utilisateur
Route::delete('/users/{user}', [UserController::class, 'destroy'])
    ->middleware(['auth:sanctum', 'admin']); // Seul un administrateur authentifié peut supprimer un utilisateur


// ======================================================
// ROUTES DES COMMANDES
// ======================================================

// Permet au client connecté de créer une commande
Route::post('/orders', [OrderController::class, 'store'])
    ->middleware('auth:sanctum');

// Permet au client connecté de consulter ses commandes
Route::get('/orders', [OrderController::class, 'index'])
    ->middleware('auth:sanctum');

// Permet au client connecté de consulter une commande précise
Route::get('/orders/{order}', [OrderController::class, 'show'])
    ->middleware('auth:sanctum');


// ======================================================
// VÉRIFICATION DE L'ADRESSE EMAIL
// ======================================================

// Permet à Laravel de vérifier l'adresse email de l'utilisateur
Route::get('/email/verify/{id}/{hash}', [VerificationController::class, 'verify'])
    ->middleware(['auth:sanctum', 'signed'])
    ->name('verification.verify');