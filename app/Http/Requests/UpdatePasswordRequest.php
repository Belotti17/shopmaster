<?php // Indique que ce fichier contient du code PHP

namespace App\Http\Requests; // Indique que cette classe appartient à l'espace de noms des requêtes HTTP

use Illuminate\Foundation\Http\FormRequest; // Importe la classe FormRequest de Laravel


class UpdatePasswordRequest extends FormRequest // Déclare la classe de validation du changement de mot de passe
{
    public function authorize(): bool // Vérifie si la requête est autorisée
    {
        return true; // Autorise la requête à passer aux règles de validation
    }


    public function rules(): array // Définit les règles de validation du changement de mot de passe
    {
    return [
        'current_password' => 'required|string', // L'ancien mot de passe est obligatoire et doit être une chaîne de caractères
        'password' => 'required|string|min:8|confirmed', // Le nouveau mot de passe est obligatoire, doit contenir au moins 8 caractères et doit être confirmé
        'password_confirmation' => 'required|string|min:8', // La confirmation du nouveau mot de passe est obligatoire et doit contenir au moins 8 caractères
    ]; // Retourne les règles de validation
    }
}