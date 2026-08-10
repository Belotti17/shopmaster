<?php // Indique que ce fichier contient du code PHP

use App\Http\Controllers\Api\AuthController; // Importe le contrôleur d'authentification
use App\Http\Controllers\Api\ClientController; // Importe le contrôleur des fonctionnalités client
use Illuminate\Http\Request; // Importe Request pour récupérer les informations de la requête
use Illuminate\Support\Facades\Route; // Importe le système de gestion des routes Laravel


Route::post('/register', [AuthController::class, 'register']); // Crée la route POST pour l'inscription d'un nouvel utilisateur

Route::post('/login', [AuthController::class, 'login']); // Crée la route POST pour connecter un utilisateur


Route::get('/user', function (Request $request) { // Crée la route GET permettant de récupérer l'utilisateur connecté
    return $request->user(); // Retourne les informations de l'utilisateur authentifié
})->middleware('auth:sanctum'); // Protège la route avec l'authentification Sanctum


Route::get('/client/profile', [ClientController::class, 'profile']) // Crée la route GET permettant au client de consulter son profil
    ->middleware('auth:sanctum'); // Vérifie que l'utilisateur est authentifié avec Sanctum


Route::get('/admin-test', function (Request $request) { // Crée temporairement une route de test pour l'administrateur
    return response()->json([ // Retourne une réponse au format JSON
        'message' => 'Bienvenue dans la zone administrateur',
        'user' => $request->user(), // Récupère les informations de l'administrateur connecté
    ]); // Termine la réponse JSON
})->middleware(['auth:sanctum', 'admin']); // Vérifie à la fois l'authentification et le rôle administrateur