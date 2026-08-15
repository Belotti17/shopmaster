<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    // Définit les champs pouvant être remplis automatiquement
    protected $fillable = [
        'order_id',
        'product_id',
        'quantity',
        'price',
    ];

    // Une ligne de commande appartient à une seule commande
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // Une ligne de commande appartient à un seul produit
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }
}