<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Exécute la migration.
     */
    public function up(): void
    {
        // Crée la table order_items
        Schema::create('order_items', function (Blueprint $table) {

            // Identifiant unique de la ligne de commande
            $table->id();

            // Identifie la commande à laquelle appartient cette ligne
            $table->foreignId('order_id')
                ->constrained()
                ->onDelete('cascade');

            // Identifie le produit commandé
            $table->foreignId('product_id')
                ->constrained()
                ->onDelete('cascade');

            // Nombre d'unités du produit commandées
            $table->unsignedInteger('quantity');

            // Prix du produit au moment de la commande
            $table->decimal('price', 10, 2);

            // Dates de création et de modification
            $table->timestamps();
        });
    }

    /**
     * Annule la migration.
     */
    public function down(): void
    {
        // Supprime la table order_items
        Schema::dropIfExists('order_items');
    }
};