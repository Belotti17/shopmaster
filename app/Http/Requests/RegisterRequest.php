<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Autorise cette requête d'inscription
    }


    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2'], // Nom obligatoire
            'email' => ['required', 'email', 'unique:users,email'], // Email valide et unique
            'password' => ['required', 'string', 'min:8'], // Mot de passe sécurisé
        ];
    }
}
