<?php

use Illuminate\Foundation\Application; // Classe principale de configuration Laravel
use Illuminate\Foundation\Configuration\Exceptions; // Configuration des exceptions
use Illuminate\Foundation\Configuration\Middleware; // Configuration des middlewares
use Illuminate\Http\Request; // Permet d'accéder à la requête HTTP

return Application::configure(basePath: dirname(__DIR__)) // Initialise Laravel
    ->withRouting( // Configure les routes de l'application
        web: __DIR__.'/../routes/web.php', // Routes web
        api: __DIR__.'/../routes/api.php', // Routes API
        commands: __DIR__.'/../routes/console.php', // Commandes Artisan
        health: '/up', // Route de vérification de l'application
    )
    ->withMiddleware(function (Middleware $middleware): void { // Configure les middlewares

        $middleware->alias([ // Crée des noms courts pour nos middlewares
            'admin' => \App\Http\Middleware\AdminMiddleware::class, // Alias "admin" pour notre middleware
        ]);

        $middleware->redirectGuestsTo(function (Request $request) { // Gère les utilisateurs non authentifiés
            if ($request->is('api/*')) { // Vérifie si la requête concerne l'API
                return null; // Ne redirige pas les requêtes API
            }

            return route('login'); // Redirige les requêtes web vers login
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void { // Configure les exceptions
        $exceptions->shouldRenderJsonWhen( // Définit quand Laravel retourne du JSON
            fn (Request $request) => $request->is('api/*'), // JSON pour les routes API
        );
    })
    ->create(); // Crée l'application Laravel