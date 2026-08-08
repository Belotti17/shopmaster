<?php

use App\Http\Controllers\Api\AuthController; // Importe le Controller d'authentification
use Illuminate\Http\Request; // Permet de récupérer la requête HTTP
use Illuminate\Support\Facades\Route; // Permet de créer des routes API

Route::post('/register', [AuthController::class, 'register']); // Route pour créer un utilisateur
Route::post('/login', [AuthController::class, 'login']); // Route pour connecter un utilisateur

Route::get('/user', function (Request $request) { // Route pour récupérer l'utilisateur connecté
    return $request->user(); // Retourne l'utilisateur authentifié
})->middleware('auth:sanctum'); // Vérifie que l'utilisateur est connecté

Route::get('/admin-test', function (Request $request) { // Route temporaire pour tester l'accès admin
    return response()->json([ // Retourne une réponse JSON
        'message' => 'Bienvenue dans la zone administrateur', // Message de confirmation
        'user' => $request->user(), // Récupère l'utilisateur connecté
    ]);
})->middleware(['auth:sanctum', 'admin']); // Vérifie connexion + rôle administrateur