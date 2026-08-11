<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    /**
     * Une catégorie peut avoir plusieurs produits.
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class); // Une catégorie possède plusieurs produits
    }
}
