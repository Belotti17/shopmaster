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
        // Crée la table orders
        Schema::create('orders', function (Blueprint $table) {

            // Identifiant unique de la commande
            $table->id();

            // Identifie l'utilisateur qui a passé la commande
            $table->foreignId('user_id')
                ->constrained()
                ->onDelete('cascade');

            // Montant total de la commande
            $table->decimal('total', 10, 2)->default(0);

            // État actuel de la commande
            $table->string('status')->default('pending');

            // Ajoute created_at et updated_at
            $table->timestamps();
        });
    }

    /**
     * Annule la migration.
     */
    public function down(): void
    {
        // Supprime la table orders si elle existe
        Schema::dropIfExists('orders');
    }
};