<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    /**
     * Récupère toutes les catégories.
     */
    public function index(): JsonResponse
    {
        $categories = Category::withCount('products')->get(); // Récupère les catégories avec le nombre de produits associés

        return response()->json([
            'message' => 'Liste des catégories récupérée avec succès',
            'categories' => $categories,
        ]);
    }

    /**
     * Crée une nouvelle catégorie.
     */
    public function store(CategoryRequest $request): JsonResponse
    {
        $category = Category::create($request->validated()); // Crée la catégorie avec uniquement les données validées

        return response()->json([
            'message' => 'Catégorie créée avec succès',
            'category' => $category,
        ], 201);
    }

    /**
     * Récupère une catégorie précise.
     */
    public function show(Category $category): JsonResponse
    {
        $category->load('products'); // Charge les produits appartenant à cette catégorie

        return response()->json([
            'message' => 'Catégorie récupérée avec succès',
            'category' => $category,
        ]);
    }

    /**
     * Modifie une catégorie existante.
     */
    public function update(CategoryRequest $request, Category $category): JsonResponse
    {
        $category->update($request->validated()); // Met à jour la catégorie avec les données validées

        return response()->json([
            'message' => 'Catégorie modifiée avec succès',
            'category' => $category->fresh()->load('products'),
        ]);
    }

    /**
     * Supprime une catégorie.
     */
    public function destroy(Category $category): JsonResponse
    {
        $category->delete(); // Supprime la catégorie de la base de données

        return response()->json([
            'message' => 'Catégorie supprimée avec succès',
        ]);
    }
}