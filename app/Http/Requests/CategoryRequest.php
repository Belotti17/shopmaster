<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CategoryRequest extends FormRequest
{
    /**
     * Détermine si l'utilisateur est autorisé à effectuer cette requête.
     */
    public function authorize(): bool
    {
        return true; // Autorise la requête ; l'accès admin sera contrôlé par le middleware
    }

    /**
     * Définit les règles de validation des catégories.
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required', // Le nom est obligatoire
                'string', // Le nom doit être une chaîne de caractères
                'max:100', // Le nom ne peut pas dépasser 100 caractères
                'unique:categories,name,' . $this->category?->id, // Le nom doit être unique, sauf lors de la modification de la catégorie actuelle
            ],

            'description' => [
                'nullable', // La description peut être vide
                'string', // Si elle est présente, elle doit être une chaîne
                'max:1000', // La description ne peut pas dépasser 1000 caractères
            ],
        ];
    }
}