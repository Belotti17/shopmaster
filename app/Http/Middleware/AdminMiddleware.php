<?php

namespace App\Http\Middleware; // Emplacement du middleware

use Closure; // Permet de continuer vers la prochaine étape
use Illuminate\Http\Request; // Représente la requête HTTP
use Symfony\Component\HttpFoundation\Response; // Type de réponse HTTP

class AdminMiddleware // Middleware chargé de vérifier le rôle admin
{
    public function handle(Request $request, Closure $next): Response // Intercepte la requête
    {
        if (!$request->user() || $request->user()->role !== 'admin') { // Vérifie que l'utilisateur est connecté et admin
            return response()->json([ // Retourne une réponse JSON
                'message' => 'Accès refusé. Administrateur uniquement.', // Message d'erreur
            ], 403); // 403 = accès interdit
        }

        return $next($request); // Autorise la requête à continuer
    }
}