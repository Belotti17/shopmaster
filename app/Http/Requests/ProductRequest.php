<?php // Indique que ce fichier contient du code PHP

namespace App\Http\Requests; // Indique que cette classe appartient à l'espace de noms des requêtes HTTP

use Illuminate\Foundation\Http\FormRequest; // Importe la classe FormRequest de Laravel

class ProductRequest extends FormRequest // Déclare la classe de validation des produits
{
    public function authorize(): bool // Vérifie si la requête est autorisée
    {
        return true; // Autorise la requête à passer aux règles de validation
    }

    public function rules(): array // Définit les règles de validation des données du produit
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'description' => ['nullable', 'string', 'max:2000'],
            'price' => ['required', 'numeric', 'min:0', 'max:999999.99'],
            'stock' => ['required', 'integer', 'min:0', 'max:100000'],
            'image' => ['nullable', 'string', 'max:255'],
            'category_id' => ['required', 'integer', 'exists:categories,id'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.min' => 'Le nom du produit doit contenir au moins 2 caractères.',
            'price.max' => 'Le prix ne peut pas dépasser 999999.99.',
            'stock.max' => 'Le stock ne peut pas dépasser 100000 unités.',
            'category_id.exists' => 'La catégorie sélectionnée est introuvable.',
        ];
    }
}
