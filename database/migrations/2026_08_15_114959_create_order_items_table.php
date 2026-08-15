<?php

// Importe la classe qui permet de créer une migration Laravel
use Illuminate\Database\Migrations\Migration;

// Importe Blueprint qui permet de définir les colonnes de la table
use Illuminate\Database\Schema\Blueprint;

// Importe Schema qui permet de créer, modifier ou supprimer des tables
use Illuminate\Support\Facades\Schema;


// Retourne une migration anonyme
return new class extends Migration
{
    // Cette méthode est exécutée lorsque Laravel lance la migration
    public function up(): void
    {
        // Crée une nouvelle table appelée "order_items"
        Schema::create('order_items', function (Blueprint $table) {

            // Crée un identifiant unique et auto-incrémenté
            $table->id();

            // Crée la colonne "order_id"
            // Elle permet de savoir à quelle commande appartient cette ligne
            $table->foreignId('order_id')

                // Indique que order_id référence la colonne "id" de la table "orders"
                ->constrained()

                // Supprime automatiquement les lignes liées si la commande est supprimée
                ->onDelete('cascade');

            // Crée la colonne "product_id"
            // Elle permet de savoir quel produit a été commandé
            $table->foreignId('product_id')

                // Indique que product_id référence la colonne "id" de la table "products"
                ->constrained()

                // Supprime automatiquement cette ligne si le produit est supprimé
                ->onDelete('cascade');

            // Crée la colonne "quantity"
            // Elle contient le nombre d'unités commandées
            $table->unsignedInteger('quantity');

            // Crée la colonne "price"
            // Elle conserve le prix du produit au moment de la commande
            $table->decimal('price', 10, 2);

            // Crée automatiquement les colonnes created_at et updated_at
            $table->timestamps();
        });
    }

    // Cette méthode est exécutée lorsque Laravel annule cette migration
    public function down(): void
    {
        // Supprime la table "order_items" si elle existe
        Schema::dropIfExists('order_items');
    }
};