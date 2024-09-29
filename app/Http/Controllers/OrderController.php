<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Services\ApiService;
use DateMalformedStringException;
use DateTimeImmutable;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;

readonly class OrderController
{
    public function __construct(private ApiService $apiService)
    {
    }

    /**
     * Get all Orders and return them in a JSON.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function list(Request $request): JsonResponse
    {
        try {
            $this->apiService->checkApiKey($request);
        } catch (UnauthorizedHttpException $e) {
            return response()->json(['error' => 'Unauthorized'], ResponseAlias::HTTP_UNAUTHORIZED);
        }

        $orders = Order::with('orderItems.product')
        ->withCount('orderItems')
        ->get();

        if ($orders->isEmpty()) {
            return response()->json(['message' => 'No order found'], 200);
        }

        return response()->json($orders);
    }

    /**
     * Create an Order and return JSON confirm
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function add(Request $request): JsonResponse
    {
        try {
            $this->apiService->checkApiKey($request);
        } catch (UnauthorizedHttpException $e) {
            return response()->json(['error' => 'Unauthorized'], ResponseAlias::HTTP_UNAUTHORIZED);
        }

        try {
            $validated = $request->validate([
                'need_by' => 'required|date',
                'items' => 'required|array',
                'items.*.product_id' => 'required|integer',
                'items.*.quantity' => 'required|integer|min:1',
            ]);


            $order = new Order();
            $order->need_by = new DateTimeImmutable($validated['need_by']);
            $order->save();

            foreach ($validated['items'] as $item) {
                $order->orderItems()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                ]);
            }

            return response()->json($order->load('orderItems.product'), 201);

        } catch (Exception $e) {
            return response()->json(['message' => 'Failed to create order', 'error' => $e->getMessage()], 400);
        }
    }

    /**
     * @param Request $request
     * @param $id
     * @return mixed
     */
    public function get(Request $request, $id): mixed
    {
        try {
            $this->apiService->checkApiKey($request);
        } catch (UnauthorizedHttpException $e) {
            return response()->json(['error' => 'Unauthorized'], ResponseAlias::HTTP_UNAUTHORIZED);
        }

        return Order::with('orderItems.product')->findOrFail($id);
    }

    /**
     * Update an Order and return confirm in JSON.
     *
     * @param Request $request
     * @param $id
     * @return JsonResponse
     * @throws DateMalformedStringException
     */
    public function update(Request $request, $id): JsonResponse
    {
        try {
            $this->apiService->checkApiKey($request);
        } catch (UnauthorizedHttpException $e) {
            return response()->json(['error' => 'Unauthorized'], ResponseAlias::HTTP_UNAUTHORIZED);
        }

        $order = Order::findOrFail($id);

        $validated = $request->validate([
            'need_by' => 'sometimes|date',
            'items' => 'sometimes|array',
            'items.*.product_id' => 'required|exists:products,id',
            'items.*.quantity' => 'required|integer|min:1',
        ]);

        if (isset($validated['need_by'])) {
            $order->need_by = new DateTimeImmutable($validated['need_by']);
        }
        $order->save();

        if (isset($validated['items'])) {
            $order->orderItems()->delete();

            foreach ($validated['items'] as $item) {
                $order->orderItems()->create([
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                ]);
            }
        }

        return response()->json($order->load('orderItems.product'));
    }

    /**
     * Delete an Order and return success in JSON.
     *
     * @param Request $request
     * @param $id
     * @return JsonResponse
     */
    public function delete(Request $request, $id): JsonResponse
    {
        try {
            $this->apiService->checkApiKey($request);
        } catch (UnauthorizedHttpException $e) {
            return response()->json(['error' => 'Unauthorized'], ResponseAlias::HTTP_UNAUTHORIZED);
        }

        $order = Order::findOrFail($id);

        // Delete related OrderItems first
        $order->orderItems()->delete();

        // Then delete the Order
        $order->delete();

        return response()->json(['message' => 'Order deleted successfully']);
    }

}
