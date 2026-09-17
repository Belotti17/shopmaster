<?php // Indique que ce fichier contient du code PHP

use Illuminate\Database\Migrations\Migration; // Importe la classe de migration Laravel
use Illuminate\Database\Schema\Blueprint; // Importe Blueprint pour modifier la table
use Illuminate\Support\Facades\Schema; // Importe la façade Schema

return new class extends Migration // Crée une migration anonyme
{
    public function up(): void // Méthode exécutée lors de la migration
    {
        Schema::table('password_reset_codes', function (Blueprint $table) { // Modifie la table existante
            $table->string('code', 255)->change(); // Permet de stocker le hash du code
        });
    }

    public function down(): void // Méthode exécutée lors d'un rollback
    {
        Schema::table('password_reset_codes', function (Blueprint $table) { // Modifie la table
            $table->string('code', 6)->change(); // Restaure l'ancienne longueur
        });
    }
};
