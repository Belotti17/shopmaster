<?php // Indique que ce fichier contient du code PHP

use App\Http\Controllers\Api\AuthController; // Importe le contrôleur d'authentification
use App\Http\Controllers\Api\ClientController; // Importe le contrôleur des fonctionnalités client
use App\Http\Controllers\Api\ProductController; // Importe le contrôleur des produits
use Illuminate\Support\Facades\Route; // Importe le système de gestion des routes Laravel


Route::post('/register', [AuthController::class, 'register']); // Crée la route POST pour l'inscription d'un nouvel utilisateur

Route::post('/login', [AuthController::class, 'login']); // Crée la route POST pour connecter un utilisateur


Route::get('/user', function () { // Crée la route GET permettant de récupérer l'utilisateur connecté
    return request()->user(); // Retourne les informations de l'utilisateur authentifié
})->middleware('auth:sanctum'); // Protège la route avec l'authentification Sanctum


Route::get('/client/profile', [ClientController::class, 'profile']) // Crée la route GET permettant au client de consulter son profil
    ->middleware('auth:sanctum'); // Vérifie que l'utilisateur est authentifié avec Sanctum

Route::put('/client/profile', [ClientController::class, 'updateProfile']) // Crée la route PUT permettant au client de modifier son profil
    ->middleware('auth:sanctum'); // Vérifie que l'utilisateur est authentifié avec Sanctum  
  
Route::put('/client/password', [ClientController::class, 'updatePassword']) // Crée la route PUT permettant au client de modifier son mot de passe
    ->middleware('auth:sanctum'); // Vérifie que l'utilisateur est authentifié avec Sanctum

Route::get('/products', [ProductController::class, 'index']); // Crée la route GET pour récupérer la liste des produits

Route::get('/products/{product}', [ProductController::class, 'show']); // Crée la route GET pour récupérer un produit précis

Route::post('/products', [ProductController::class, 'store']) // Crée la route POST pour créer un produit
    ->middleware(['auth:sanctum', 'admin']); // Autorise uniquement un administrateur authentifié

Route::put('/products/{product}', [ProductController::class, 'update']) // Crée la route PUT pour modifier un produit
    ->middleware(['auth:sanctum', 'admin']); // Autorise uniquement un administrateur authentifié

Route::delete('/products/{product}', [ProductController::class, 'destroy']) // Crée la route DELETE pour supprimer un produit
    ->middleware(['auth:sanctum', 'admin']); // Autorise uniquement un administrateur authentifié