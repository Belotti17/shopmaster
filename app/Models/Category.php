<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    /**
     * Champs autorisés lors de la création ou modification
     * d'une catégorie.
     */
    protected $fillable = [
        'name', // Nom de la catégorie
        'description', // Description de la catégorie
    ];

    /**
     * Une catégorie peut avoir plusieurs produits.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class); // Une catégorie possède plusieurs produits
    }
}
