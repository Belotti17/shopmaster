<?php // Indique que ce fichier contient du code PHP

use Illuminate\Database\Migrations\Migration; // Importe la classe de base utilisée pour créer une migration
use Illuminate\Database\Schema\Blueprint; // Importe Blueprint, qui permet de définir ou modifier la structure d'une table
use Illuminate\Support\Facades\Schema; // Importe la façade Schema, utilisée pour modifier les tables de la base de données

return new class extends Migration // Crée une classe de migration anonyme qui hérite de la classe Migration
{
    /**
     * Exécute la migration.
     */
    public function up(): void // Méthode exécutée lorsque nous lançons "php artisan migrate"
    {
        Schema::table('products', function (Blueprint $table) { // Modifie la table "products" existante
            $table->foreignId('category_id') // Crée une colonne category_id destinée à contenir l'identifiant d'une catégorie
                ->nullable() // Autorise la colonne category_id à être vide (NULL)
                ->constrained('categories') // Crée une clé étrangère vers la colonne "id" de la table "categories"
                ->nullOnDelete(); // Si la catégorie est supprimée, category_id devient NULL au lieu de supprimer le produit
        }); // Termine la modification de la table products
    } // Termine la méthode up

    /**
     * Annule la migration.
     */
    public function down(): void // Méthode exécutée lorsque nous annulons la migration
    {
        Schema::table('products', function (Blueprint $table) { // Modifie à nouveau la table "products"
            $table->dropForeign(['category_id']); // Supprime la contrainte de clé étrangère liée à category_id
            $table->dropColumn('category_id'); // Supprime complètement la colonne category_id
        }); // Termine la modification de la table products
    } // Termine la méthode down
}; // Termine la classe de migration