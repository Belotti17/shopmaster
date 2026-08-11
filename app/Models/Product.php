<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    /**
     * Les champs autorisés lors de la création ou modification d'un produit.
     */
    protected $fillable = [
        'name', // Nom du produit
        'description', // Description du produit
        'price', // Prix du produit
        'stock', // Quantité disponible en stock
        'image', // Nom ou chemin de l'image du produit
        'category_id', // Identifiant de la catégorie du produit
    ];

    /**
     * Un produit appartient à une seule catégorie.
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class); // Relie le produit à sa catégorie
    }
}
