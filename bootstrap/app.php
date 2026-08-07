<?php

use Illuminate\Foundation\Application; // Classe principale de configuration de Laravel
use Illuminate\Foundation\Configuration\Exceptions; // Permet de configurer la gestion des exceptions
use Illuminate\Foundation\Configuration\Middleware; // Permet de configurer les middlewares
use Illuminate\Http\Request; // Permet d'accéder aux informations de la requête HTTP

return Application::configure(basePath: dirname(__DIR__)) // Initialise Laravel avec le dossier racine du projet
    ->withRouting( // Configure les différentes routes de l'application
        web: __DIR__.'/../routes/web.php', // Charge les routes web
        api: __DIR__.'/../routes/api.php', // Charge les routes API
        commands: __DIR__.'/../routes/console.php', // Charge les commandes Artisan personnalisées
        health: '/up', // Définit l'URL utilisée pour vérifier que l'application fonctionne
    )
    ->withMiddleware(function (Middleware $middleware): void { // Configure les middlewares de l'application
        $middleware->redirectGuestsTo(function (Request $request) { // Définit le comportement des utilisateurs non authentifiés
            if ($request->is('api/*')) { // Vérifie si la requête concerne notre API
                return null; // Ne redirige pas les requêtes API vers une page login
            }

            return route('login'); // Les requêtes web peuvent encore être redirigées vers login
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void { // Configure la gestion des exceptions
        $exceptions->shouldRenderJsonWhen( // Indique à Laravel quand retourner une réponse JSON
            fn (Request $request) => $request->is('api/*'), // Retourne du JSON pour toutes les routes API
        );
    })
    ->create(); // Crée et retourne l'application Laravel