<?php

namespace App\Http\Requests; // Indique que cette classe appartient aux requêtes HTTP

use Illuminate\Foundation\Http\FormRequest; // Importe la classe FormRequest de Laravel

class PasswordRequest extends FormRequest // Déclare la classe de validation du mot de passe
{
    // Détermine si la requête est autorisée
    public function authorize(): bool
    {
        return true; // Autorise la requête à continuer vers les règles de validation
    }

    // Définit les règles de validation du mot de passe
    public function rules(): array
    {
        return [
            'password' => 'required|string|min:8|confirmed',
            // Le mot de passe est obligatoire
            // Il doit être une chaîne de caractères
            // Il doit contenir au minimum 8 caractères
            // "confirmed" exige un champ password_confirmation identique
        ];
    }
}