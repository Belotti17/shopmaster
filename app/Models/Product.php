<?php // Indique que ce fichier contient du code PHP

namespace App\Models; // Indique que le modèle appartient à l'espace de noms Models

use Illuminate\Database\Eloquent\Model; // Importe le modèle Eloquent de Laravel

class Product extends Model // Déclare le modèle Product
{
    protected $fillable = [ // Définit les champs pouvant être remplis automatiquement
        'name', // Autorise l'enregistrement du nom du produit
        'description', // Autorise l'enregistrement de la description du produit
        'price', // Autorise l'enregistrement du prix du produit
        'stock', // Autorise l'enregistrement du stock disponible
        'image', // Autorise l'enregistrement du chemin de l'image
    ];
}