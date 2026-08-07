<?php
// Indique que ce fichier utilise le langage PHP


use Illuminate\Database\Migrations\Migration;
// Importe la classe Migration de Laravel.
// Elle permet de créer des fichiers qui vont modifier la structure de la base de données.


use Illuminate\Database\Schema\Blueprint;
// Importe Blueprint.
// Il permet de définir les changements à appliquer sur une table
// comme ajouter une colonne, modifier une colonne, supprimer une colonne...


use Illuminate\Support\Facades\Schema;
// Importe la façade Schema.
// Elle permet à Laravel de communiquer avec la structure de la base de données
// (créer une table, modifier une table, supprimer une table...).


return new class extends Migration
// Retourne une classe anonyme qui hérite de Migration.
// Laravel utilise cette classe pour exécuter cette migration.
// "extends Migration" signifie que notre classe possède les fonctionnalités
// nécessaires pour appliquer et annuler une modification.


{
    /**
     * Run the migrations.
     */
    public function up(): void
    // La méthode up() contient les instructions qui seront exécutées
    // quand on lance : php artisan migrate
    //
    // C'est ici qu'on ajoute la colonne role dans la table users.
    //
    // ": void" signifie que cette fonction ne retourne aucune valeur.
    {

        Schema::table('users', function (Blueprint $table) {
            // Schema::table() indique à Laravel qu'on veut modifier
            // une table existante.
            //
            // Ici on modifie la table "users".
            //
            // La fonction reçoit un objet Blueprint appelé $table.
            // Cet objet représente la table users et permet de lui ajouter
            // des modifications.


            $table->string('role')
                  // Crée une nouvelle colonne appelée "role".
                  //
                  // string signifie que cette colonne va stocker du texte.
                  //
                  // Exemple de valeurs possibles :
                  // "admin"
                  // "client"


                  ->default('client')
                  // Définit une valeur par défaut.
                  //
                  // Cela signifie que si un utilisateur est créé
                  // sans préciser son rôle,
                  // Laravel enregistrera automatiquement :
                  //
                  // role = "client"


                  ->after('password');
                  // Place la colonne role juste après la colonne password
                  // dans la structure de la table.
                  //
                  // Cela change seulement l'ordre visuel des colonnes,
                  // pas le fonctionnement.
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    // La méthode down() sert à annuler la migration.
    //
    // Elle est exécutée quand on utilise :
    //
    // php artisan migrate:rollback
    {

        Schema::table('users', function (Blueprint $table) {
            // On indique encore à Laravel qu'on travaille
            // sur la table users.


            $table->dropColumn('role');
            // Supprime la colonne role.
            //
            // Si on annule la migration,
            // Laravel retirera cette colonne
            // pour revenir à l'état précédent.
        });
    }
};