<?php

use App\Http\Controllers\Api\AuthController; // Importe le Controller d'authentification
use Illuminate\Http\Request; // Permet de récupérer la requête HTTP
use Illuminate\Support\Facades\Route; // Permet de créer des routes API


Route::post('/register', [AuthController::class, 'register']); // Route API pour créer un utilisateur
Route::post('/login', [AuthController::class, 'login']); // Route API pour connecter un utilisateur

Route::get('/user', function (Request $request) { // Route pour récupérer l'utilisateur connecté
    return $request->user(); // Retourne l'utilisateur authentifié
})->middleware('auth:sanctum'); // Protège la route avec Sanctum
