<?php

namespace App\Models; // Namespace du modèle User

use Database\Factories\UserFactory; // Factory pour créer des utilisateurs de test
use Illuminate\Database\Eloquent\Attributes\Fillable; // Définit les champs remplissables
use Illuminate\Database\Eloquent\Attributes\Hidden; // Définit les champs à cacher
use Illuminate\Database\Eloquent\Factories\HasFactory; // Active les factories Eloquent
use Illuminate\Foundation\Auth\User as Authenticatable; // Classe de base pour l'authentification
use Illuminate\Notifications\Notifiable; // Permet d'envoyer des notifications
use Laravel\Sanctum\HasApiTokens; // Permet à User d'utiliser les tokens Sanctum

#[Fillable(['name', 'email', 'password', 'role'])] // Champs autorisés lors du remplissage du modèle
#[Hidden(['password', 'remember_token'])] // Champs cachés dans les réponses JSON
class User extends Authenticatable // Modèle User basé sur l'authentification Laravel
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable; // Sanctum + factories + notifications

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array // Définit le type de certains attributs
    {
        return [
            'email_verified_at' => 'datetime', // Convertit la vérification email en date
            'password' => 'hashed', // Hash automatiquement le mot de passe
        ];
    }
}