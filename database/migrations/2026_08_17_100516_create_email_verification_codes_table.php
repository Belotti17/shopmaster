<?php

use Illuminate\Database\Migrations\Migration; // Permet de créer une migration
use Illuminate\Database\Schema\Blueprint; // Permet de définir les colonnes de la table
use Illuminate\Support\Facades\Schema; // Permet de créer et supprimer des tables

return new class extends Migration
{
    /**
     * Crée la table des codes de vérification email.
     */
    public function up(): void
    {
        // Crée la table email_verification_codes
        Schema::create('email_verification_codes', function (Blueprint $table) {

            // Identifiant unique du code
            $table->id();

            // Identifiant de l'utilisateur concerné
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // Code de vérification à 6 chiffres
            $table->string('code', 6);

            // Date et heure d'expiration du code
            $table->timestamp('expires_at');

            // Colonnes created_at et updated_at
            $table->timestamps();
        });
    }

    /**
     * Supprime la table des codes de vérification.
     */
    public function down(): void
    {
        // Supprime la table si elle existe
        Schema::dropIfExists('email_verification_codes');
    }
};