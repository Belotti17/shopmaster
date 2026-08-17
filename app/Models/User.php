<?php

namespace App\Models; // Namespace du modèle User

use Database\Factories\UserFactory; // Factory pour créer des utilisateurs de test
use Illuminate\Contracts\Auth\MustVerifyEmail; // Permet d'activer la vérification de l'adresse email
use Illuminate\Database\Eloquent\Attributes\Fillable; // Définit les champs remplissables
use Illuminate\Database\Eloquent\Attributes\Hidden; // Définit les champs à cacher
use Illuminate\Database\Eloquent\Factories\HasFactory; // Active les factories Eloquent
use Illuminate\Database\Eloquent\Relations\HasMany; // Permet de définir une relation "un vers plusieurs"
use Illuminate\Foundation\Auth\User as Authenticatable; // Classe de base pour l'authentification
use Illuminate\Notifications\Notifiable; // Permet d'envoyer des notifications
use Laravel\Sanctum\HasApiTokens; // Permet à User d'utiliser les tokens Sanctum
use App\Models\Order; // Importe le modèle Order
use App\Models\EmailVerificationCode; // Importe le modèle des codes de vérification


#[Fillable(['name', 'email', 'password', 'role'])] // Champs autorisés lors du remplissage du modèle
#[Hidden(['password', 'remember_token'])] // Champs cachés dans les réponses JSON
class User extends Authenticatable implements MustVerifyEmail // Modèle User avec vérification d'email
{
    /** @use HasFactory<UserFactory> */
    use HasApiTokens, HasFactory, Notifiable; // Active Sanctum, les factories et les notifications


    /**
     * Définit la relation entre User et Order.
     */
    public function orders(): HasMany // Un utilisateur peut avoir plusieurs commandes
    {
        return $this->hasMany(Order::class); // Retourne toutes les commandes appartenant à cet utilisateur
    }


    /**
     * Définit la relation entre User et les codes de vérification.
     */
    public function emailVerificationCodes(): HasMany // Un utilisateur peut avoir plusieurs codes
    {
        return $this->hasMany(EmailVerificationCode::class); // Retourne les codes de vérification de l'utilisateur
    }


    /**
     * Définit les types de certains attributs.
     */
    protected function casts(): array // Définit le type de certains attributs
    {
        return [
            'email_verified_at' => 'datetime', // Convertit la vérification email en date
            'password' => 'hashed', // Hash automatiquement le mot de passe
        ];
    }
}