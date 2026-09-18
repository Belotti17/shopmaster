<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ProductCatalogAdvancedTest extends TestCase
{
    use RefreshDatabase;

    public function test_products_can_be_searched_filtered_sorted_and_paginated(): void
    {
        $electronics = Category::create([
            'name' => 'Electronics',
            'description' => 'Electronic devices',
        ]);

        $books = Category::create([
            'name' => 'Books',
            'description' => 'Books and guides',
        ]);

        Product::create([
            'name' => 'Smartphone Pro',
            'description' => 'A premium smartphone for daily use',
            'price' => 899.99,
            'stock' => 10,
            'image' => 'smartphone.jpg',
            'category_id' => $electronics->id,
        ]);

        Product::create([
            'name' => 'Wireless Mouse',
            'description' => 'Comfortable mouse for office use',
            'price' => 49.99,
            'stock' => 20,
            'image' => 'mouse.jpg',
            'category_id' => $electronics->id,
        ]);

        Product::create([
            'name' => 'Laravel Guide',
            'description' => 'A complete guide to Laravel development',
            'price' => 29.99,
            'stock' => 15,
            'image' => 'guide.jpg',
            'category_id' => $books->id,
        ]);

        $response = $this->getJson('/api/products?search=phone&category_id=' . $electronics->id . '&min_price=200&max_price=1000&sort=price_asc&per_page=1');

        $response->assertOk()
            ->assertJsonStructure([
                'products',
                'pagination',
            ])
            ->assertJsonPath('pagination.total', 1)
            ->assertJsonPath('pagination.per_page', 1)
            ->assertJsonPath('pagination.current_page', 1)
            ->assertJsonPath('products.0.name', 'Smartphone Pro');
    }
}
