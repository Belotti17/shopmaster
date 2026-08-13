<?php

namespace App\Http\Requests; // Indique que cette classe appartient aux requêtes HTTP de l'application

use Illuminate\Contracts\Validation\ValidationRule; // Importe le type utilisé pour les règles de validation
use Illuminate\Foundation\Http\FormRequest; // Importe la classe FormRequest de Laravel
use Illuminate\Validation\Rule; // Permet de construire des règles de validation avancées

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

            'email' => [
                'required', // L'email est obligatoire
                'email', // L'email doit avoir un format valide
                'max:255', // L'email ne doit pas dépasser 255 caractères
                Rule::unique('users', 'email')->ignore($this->user), // L'email doit être unique sauf pour l'utilisateur actuellement modifié
            ],

            'role' => 'required|in:admin,client', // Le rôle est obligatoire et doit uniquement être admin ou client
        ];
    }
}