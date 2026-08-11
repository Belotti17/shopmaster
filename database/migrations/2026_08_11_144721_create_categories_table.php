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
        Schema::create('categories', function (Blueprint $table) {
            $table->id(); // Identifiant unique de la catégorie
            $table->string('name'); // Nom de la catégorie
            $table->text('description')->nullable(); // Description facultative
            $table->timestamps(); // Date de création et de modification
        });
    }

    /**
     * Annule la migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('categories'); // Supprime la table categories
    }
};