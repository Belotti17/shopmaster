<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    // Récupère le profil de l'utilisateur actuellement connecté
    public function profile(Request $request)
    {
        // Retourne une réponse au format JSON
        return response()->json([
            // Message envoyé au client
            'message' => 'Profil récupéré avec succès',

            // Récupère l'utilisateur connecté grâce au token Sanctum
            'user' => $request->user(),
        ]);
    }
}