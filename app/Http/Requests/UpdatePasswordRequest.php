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
            // Les règles de validation seront ajoutées à l'étape suivante
        ]; // Retourne actuellement une liste vide de règles
    }
}