<?php

namespace App\Services;

use App\Repositories\OrderRepositoryInterface;
use Carbon\Carbon;

readonly class ScheduleService
{
    public function __construct(
        private OrderRepositoryInterface          $orderRepository,
        private ProductionTimeCalculatorInterface $productionTimeCalculator,
        private ChangeoverDelayService            $changeoverDelayService
    )
    {
    }

    public function calculateSchedule(): array
    {
        $delayMinutes = $this->changeoverDelayService->getDelay();
        $currentTime = Carbon::now();

        $orders = $this->orderRepository->getOrdersDueAfter($currentTime);
        $schedule = [];
        $lastProductType = null;

        foreach ($orders as $order) {
            $productionInfo = $this->productionTimeCalculator->calculate($order, $delayMinutes, $lastProductType);

            $orderStartTime = $currentTime->copy();
            $orderEndTime = $orderStartTime->copy()->addMinutes($productionInfo->totalProductionTime);

            if ($orderEndTime > Carbon::parse($order->need_by)) {
                $earliestStart = Carbon::parse($order->need_by);
                $newStartTime = $earliestStart->copy()->subMinutes($productionInfo->totalProductionTime);

                $orderStartTime = $newStartTime;
                $orderEndTime = $newStartTime->copy()->addMinutes($productionInfo->totalProductionTime);
            }

            $schedule[] = [
                'order_id' => $order->id,
                'product_name' => $order->orderItems->map(fn($item) => $item->product->name)->unique()->implode(', '),
                'quantity' => $order->orderItems->sum('quantity'),
                'start_time' => $orderStartTime->toDateTimeString(),
                'end_time' => $orderEndTime->toDateTimeString(),
                'need_by' => $order->need_by->toDateTimeString(),
                'production_time' => $productionInfo->totalProductionTime,
            ];

            $currentTime = $orderEndTime;
        }

        return $schedule;
    }
}
