<?php

namespace App\Http\Controllers;

use App\Services\ApiService;
use App\Services\ScheduleService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

readonly class ScheduleController
{
    public function __construct(
        private ScheduleService $scheduleService,
        private ApiService      $apiService
    )
    {
    }

    public function calculateSchedule(Request $request): JsonResponse
    {
        try {
            $this->apiService->checkApiKey($request);
        } catch (UnauthorizedHttpException $e) {
            return response()->json(['error' => 'Unauthorized'], ResponseAlias::HTTP_UNAUTHORIZED);
        }

        // Find the best planning
        $scheduledOrders = $this->scheduleService->calculateSchedule();

        return response()->json($scheduledOrders);
    }
}
