<?php

namespace App\Services;

use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

class ApiService
{
    public function checkApiKey(Request $request): void
    {
        $providedApiKey = $request->header('API_KEY');
        $apiKey = config('services.api_key');

        if (empty($providedApiKey) || $providedApiKey !== $apiKey) {
            throw new UnauthorizedHttpException('', 'Unauthorized: Invalid API Key');
        }
    }
}
