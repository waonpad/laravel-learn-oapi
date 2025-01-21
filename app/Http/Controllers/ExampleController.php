<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Http\JsonResponse;

class ExampleController extends Controller
{
    public function index(): JsonResponse
    {
        return new JsonResponse(['message' => 'Hello, World!']);
    }
}
