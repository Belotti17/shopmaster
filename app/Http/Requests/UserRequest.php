<?php

namespace App\Http\Requests; // Indique que cette classe appartient aux requêtes HTTP de l'application

use Illuminate\Contracts\Validation\ValidationRule; // Importe le type utilisé pour les règles de validation
use Illuminate\Foundation\Http\FormRequest; // Importe la classe FormRequest de Laravel

class UserRequest extends FormRequest // Déclare la classe de validation des utilisateurs
{
    /**
     * Détermine si la requête est autorisée.
     */
    public function authorize(): bool
    {
        return true; // Autorise la requête à continuer vers les règles de validation
    }

    /**
     * Définit les règles de validation des données utilisateur.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => 'required|string|max:255', // Le nom est obligatoire, doit être du texte et ne doit pas dépasser 255 caractères

            'email' => 'required|email|max:255', // L'email est obligatoire, doit avoir un format valide et ne doit pas dépasser 255 caractères

            'role' => 'required|in:admin,client', // Le rôle est obligatoire et doit uniquement être admin ou client
        ];
    }
}