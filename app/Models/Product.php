<?php

// Définit l'espace de noms du modèle
namespace App\Models;

// Importe la classe permettant de définir une relation "plusieurs"
use Illuminate\Database\Eloquent\Relations\HasMany;

// Importe le modèle principal Eloquent
use Illuminate\Database\Eloquent\Model;

// Importe le modèle OrderItem
use App\Models\OrderItem;


// Déclare le modèle Product
class Product extends Model
{
    // Définit les champs pouvant être remplis automatiquement
    protected $fillable = [
        'name',        // Nom du produit
        'description', // Description du produit
        'price',       // Prix actuel du produit
        'stock',       // Quantité disponible en stock
        'image',       // Image du produit
        'category_id', // Catégorie du produit
    ];

    // Définit la relation entre Product et OrderItem
    public function orderItems(): HasMany
    {
        // Un produit peut apparaître dans plusieurs lignes de commande
        return $this->hasMany(OrderItem::class);
    }
}