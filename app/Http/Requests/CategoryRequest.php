<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CategoryRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à effectuer cette requête.
     */
    public function authorize(): bool
    {
        return true; // Autorise la requête ; le middleware admin protège déjà la route
    }

    /**
     * Définit les règles de validation des catégories.
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required', // Le nom de la catégorie est obligatoire
                'string', // Le nom doit être une chaîne de caractères
                'max:100', // Le nom ne peut pas dépasser 100 caractères

                Rule::unique('categories', 'name')
                    ->ignore($this->route('category')?->id), // Ignore la catégorie actuelle lors d'une modification
            ],

            'description' => [
                'nullable', // La description peut être absente ou vide
                'string', // La description doit être une chaîne de caractères
                'max:1000', // La description ne peut pas dépasser 1000 caractères
            ],
        ];
    }
}