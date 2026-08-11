<?php // Indique que ce fichier contient du code PHP

namespace App\Http\Requests; // Indique que cette classe appartient à l'espace de noms des requêtes HTTP

use Illuminate\Contracts\Validation\ValidationRule; // Importe le type utilisé pour les règles de validation
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
            'name' => 'required|string|max:255', // Le nom est obligatoire, doit être du texte et ne doit pas dépasser 255 caractères
            'description' => 'nullable|string', // La description est facultative mais doit être du texte si elle est fournie
            'price' => 'required|numeric|min:0', // Le prix est obligatoire, doit être numérique et ne peut pas être négatif
            'stock' => 'required|integer|min:0', // Le stock est obligatoire, doit être un entier et ne peut pas être négatif
            'image' => 'nullable|string|max:255', // L'image est facultative et son chemin ne doit pas dépasser 255 caractères
        ]; // Retourne toutes les règles de validation
    }
}