<?php

namespace App\Http\Requests; // Emplacement de notre classe de validation

use Illuminate\Contracts\Validation\ValidationRule; // Type utilisé pour les règles de validation
use Illuminate\Foundation\Http\FormRequest; // Classe Laravel pour gérer la validation


class LoginRequest extends FormRequest // Classe de validation pour la connexion
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool // Vérifie si la requête est autorisée
    {
        return true; // Autorise la tentative de connexion
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array // Définit les règles de validation
    {
        return [
            'email' => ['required', 'email'], // Email obligatoire avec un format valide
            'password' => ['required', 'string'], // Mot de passe obligatoire et texte
        ];
    }
}
