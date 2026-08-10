<?php // Indique que ce fichier contient du code PHP

use Illuminate\Database\Migrations\Migration; // Importe la classe de migration Laravel
use Illuminate\Database\Schema\Blueprint; // Permet de définir les colonnes de la table
use Illuminate\Support\Facades\Schema; // Permet de créer et modifier les tables


return new class extends Migration // Crée une migration anonyme
{
    public function up(): void // Méthode exécutée lorsque la migration est appliquée
    {
        Schema::create('products', function (Blueprint $table) { // Crée la table products
            $table->id(); // Crée un identifiant unique auto-incrémenté
            $table->string('name'); // Stocke le nom du produit
            $table->text('description')->nullable(); // Stocke la description du produit, facultative
            $table->decimal('price', 10, 2); // Stocke le prix avec deux chiffres après la virgule
            $table->unsignedInteger('stock')->default(0); // Stocke la quantité disponible, avec 0 par défaut
            $table->string('image')->nullable(); // Stocke le chemin de l'image, facultatif
            $table->timestamps(); // Crée created_at et updated_at
        }); // Termine la création de la table products
    } // Termine la méthode up

    public function down(): void // Méthode exécutée lorsqu'on annule la migration
    {
        Schema::dropIfExists('products'); // Supprime la table products si elle existe
    } // Termine la méthode down
};