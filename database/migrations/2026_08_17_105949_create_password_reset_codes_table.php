<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Crée la table des codes de réinitialisation du mot de passe.
     */
    public function up(): void
    {
        Schema::create('password_reset_codes', function (Blueprint $table) {

            // Identifiant unique du code
            $table->id();

            // Identifiant de l'utilisateur concerné
            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // Code de réinitialisation à 6 chiffres
            $table->string('code', 6);

            // Date et heure d'expiration du code
            $table->timestamp('expires_at');

            // Colonnes created_at et updated_at
            $table->timestamps();
        });
    }

    /**
     * Supprime la table des codes de réinitialisation.
     */
    public function down(): void
    {
        Schema::dropIfExists('password_reset_codes');
    }
};