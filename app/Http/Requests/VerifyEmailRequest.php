<?php

namespace App\Http\Requests; // Namespace de la requête

use Illuminate\Foundation\Http\FormRequest; // Classe FormRequest de Laravel

class VerifyEmailRequest extends FormRequest
{
    /**
     * Détermine si la requête est autorisée.
     */
    public function authorize(): bool
    {
        // Autorise la requête
        return true;
    }

    /**
     * Définit les règles de validation.
     */
    public function rules(): array
    {
        return [
            // Vérifie que l'email existe dans la table users
            'email' => 'required|email|exists:users,email',

            // Vérifie que le code contient exactement 6 chiffres
            'code' => [
                'required',
                'string',
                'size:6',
                'regex:/^[0-9]{6}$/',
            ],
        ];
    }

    /**
     * Définit les messages d'erreur personnalisés.
     */
    public function messages(): array
    {
        return [
            'email.required' => 'L’adresse email est obligatoire.',
            'email.email' => 'L’adresse email est invalide.',
            'email.exists' => 'Aucun utilisateur ne possède cette adresse email.',

            'code.required' => 'Le code de vérification est obligatoire.',
            'code.size' => 'Le code doit contenir exactement 6 chiffres.',
            'code.regex' => 'Le code doit contenir uniquement des chiffres.',
        ];
    }
}