<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Services\ApiService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Illuminate\Http\Request;

readonly class ProductController
{
    public function __construct(private ApiService $apiService)
    {
    }

    public function get(Request $request): Collection|JsonResponse
    {
        try {
            $this->apiService->checkApiKey($request);
        } catch (UnauthorizedHttpException $e) {
            return response()->json(['error' => 'Unauthorized'], ResponseAlias::HTTP_UNAUTHORIZED);
        }

        return Product::all();
    }
}
