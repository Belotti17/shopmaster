<?php // Indique que ce fichier contient du code PHP

namespace App\Http\Requests; // Indique que cette classe appartient à l'espace de noms des requêtes HTTP

use Illuminate\Foundation\Http\FormRequest; // Importe la classe FormRequest de Laravel


class UpdateProfileRequest extends FormRequest // Déclare la classe de validation de la modification du profil
{
    public function authorize(): bool // Vérifie si la requête est autorisée
    {
        return true; // Autorise la requête à passer aux règles de validation
    }


    public function rules(): array // Définit les règles de validation des informations du profil
    {
        return [
            'name' => 'required|string|max:255', // Le nom est obligatoire, doit être du texte et ne doit pas dépasser 255 caractères
            'email' => 'required|email|max:255|unique:users,email,' . $this->user()->id, // L'email est obligatoire, doit être valide et doit être unique sauf celui de l'utilisateur connecté
        ]; // Retourne les règles de validation
    }
}