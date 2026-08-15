<?php

namespace App\Models; // Définit l'espace de noms du modèle Product

use Illuminate\Database\Eloquent\Model; // Importe le modèle Eloquent de base
use Illuminate\Database\Eloquent\Relations\BelongsTo; // Permet de définir une relation "appartient à"
use Illuminate\Database\Eloquent\Relations\HasMany; // Permet de définir une relation "un vers plusieurs"
use App\Models\OrderItem; // Importe le modèle OrderItem


class Product extends Model // Déclare le modèle Product
{
    /**
     * Les champs autorisés lors de la création ou modification d'un produit.
     */
    protected $fillable = [
        'name', // Nom du produit
        'description', // Description du produit
        'price', // Prix actuel du produit
        'stock', // Quantité disponible en stock
        'image', // Nom ou chemin de l'image du produit
        'category_id', // Identifiant de la catégorie du produit
    ];

    /**
     * Un produit appartient à une seule catégorie.
     */
    public function category(): BelongsTo // Définit la relation Product → Category
    {
        return $this->belongsTo(Category::class); // Relie le produit à sa catégorie
    }

    /**
     * Un produit peut apparaître dans plusieurs lignes de commande.
     */
    public function orderItems(): HasMany // Définit la relation Product → OrderItem
    {
        return $this->hasMany(OrderItem::class); // Récupère toutes les lignes contenant ce produit
    }
}