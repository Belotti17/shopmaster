<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;

class DocsController extends Controller
{
    public function index(): JsonResponse
    {
        return response()->json([
            'app' => 'ShopMaster API',
            'version' => '1.0.0',
            'base_url' => '/api',
            'endpoints' => [
                'auth' => [
                    'POST /register',
                    'POST /login',
                    'POST /login/verify-otp',
                    'POST /login/resend-otp',
                    'POST /logout',
                ],
                'profile' => [
                    'GET /user',
                    'GET /profile',
                    'PUT /profile',
                    'PUT /profile/password',
                ],
                'products' => [
                    'GET /products?search=&category_id=&min_price=&max_price=&in_stock=&sort=&per_page=',
                    'GET /products/{id}',
                    'POST /products',
                    'PUT /products/{id}',
                    'DELETE /products/{id}',
                ],
                'categories' => [
                    'GET /categories',
                    'GET /categories/{id}',
                    'POST /categories',
                    'PUT /categories/{id}',
                    'DELETE /categories/{id}',
                ],
                'orders' => [
                    'POST /orders',
                    'GET /orders',
                    'GET /orders/{id}',
                ],
                'users' => [
                    'GET /users',
                    'GET /users/{id}',
                    'PUT /users/{id}',
                    'DELETE /users/{id}',
                ],
                'security' => [
                    'POST /email/verify',
                    'POST /register/verify-otp',
                    'POST /password/forgot',
                    'POST /password/verify',
                    'POST /password/reset',
                ],
            ],
        ]);
    }
}
