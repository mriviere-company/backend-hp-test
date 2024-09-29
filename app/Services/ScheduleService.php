<?php

namespace App\Services;

use App\Models\ChangeoverDelay;
use App\Models\Order;
use App\Models\ProductionInfo;
use App\Models\ProductionSpeed;
use Carbon\Carbon;

class ScheduleService
{
    public function calculateSchedule(): array
    {
        // Get delay for switching product in production
        $delayMinutes = ChangeoverDelay::first();

        $currentTime = Carbon::now();

        // Get all Order that aren't in the past
        $orders = Order::with(['orderItems.product'])
            ->where('need_by', '>', $currentTime)
            ->get();

        // Get speed of production per type
        $productionSpeeds = ProductionSpeed::all()->keyBy('product_type');

        // Product time and slack time for every Order
        $orders = $orders->map(function ($order) use ($productionSpeeds, $delayMinutes, $currentTime) {
            $lastProductType = null;

            $productionInfo = $this->getTotalProductTimeAndProductType($order, $delayMinutes, $lastProductType, $productionSpeeds);

            // Calculate the end_time and slack_time for each Order
            $order->total_production_time = $productionInfo->totalProductionTime;
            $orderEndTime = $currentTime->copy()->addMinutes($productionInfo->totalProductionTime);
            $order->end_time = $orderEndTime;
            $order->slack_time = Carbon::parse($order->need_by)->getTimestamp() - $orderEndTime->getTimestamp();

            return $order;
        });

        // Prioritize orders based on their ability to meet the need_by deadline
        $priorityOrders = $orders->filter(function ($order) {
            return $order->slack_time < 0; // Order that is over the need_by
        })->sortBy('slack_time'); // Prioritize the most delayed orders

        $remainingOrders = $orders->filter(function ($order) {
            return $order->slack_time >= 0; // Order with need_by at time
        })->sortBy('slack_time'); // Sort by slack_time

        // Combine the priority orders and the remaining ones
        $orderedList = $priorityOrders->concat($remainingOrders);

        $schedule = [];
        $lastProductType = null;

        // Order scheduling while minimizing changeover delays
        foreach ($orderedList as $order) {
            $orderStartTime = $currentTime->copy();

            $productionInfo = $this->getTotalProductTimeAndProductType($order, $delayMinutes, $lastProductType, $productionSpeeds);

            // Calculate the end time for this order
            $orderEndTime = $orderStartTime->copy()->addMinutes($productionInfo->totalProductionTime);

            // Adjust the schedule to meet the constraints
            if ($orderEndTime > Carbon::parse($order->need_by)) {
                // Check if the previous order needs to be rescheduled
                $earliestStart = Carbon::parse($order->need_by);
                $newStartTime = $earliestStart->copy()->subMinutes($productionInfo->totalProductionTime);

                // Readjust the start and end time of the order
                $orderStartTime = $newStartTime;
                $orderEndTime = $newStartTime->copy()->addMinutes($productionInfo->totalProductionTime);
            }

            // Record the order in the schedule
            $schedule[] = [
                'order_id' => $order->id,
                'product_name' => $order->orderItems->map(fn($item) => $item->product->name)->unique()->implode(', '),
                'quantity' => $order->orderItems->sum('quantity'),
                'start_time' => $orderStartTime->toDateTimeString(),
                'end_time' => $orderEndTime->toDateTimeString(),
                'need_by' => $order->need_by->toDateTimeString(),
                'production_time' => $productionInfo->totalProductionTime,
            ];

            // Update the current time to the end of the order
            $currentTime = $orderEndTime;

        }

        return $schedule;
    }

    public function getTotalProductTimeAndProductType(Order $order, $delayMinutes, ?string $lastProductType, $productionSpeeds): ProductionInfo
    {
        $totalProductionTime = 0;
        $productType = null;

        foreach ($order->orderItems as $orderItem) {
            $productType = $orderItem->product->type;
            $quantity = $orderItem->quantity;

            if (isset($productionSpeeds[$productType]) && $quantity >= 1) {
                $unitsPerHour = $productionSpeeds[$productType]->units_per_hour;
                $unitsPerMinute = $unitsPerHour / 60;
                $productionTime = $quantity / $unitsPerMinute;
            } else {
                continue;
            }

            // Add the changeover delay if necessary
            if ($lastProductType && $lastProductType !== $productType) {
                $totalProductionTime += $delayMinutes->delay_minutes ?? 0;
            }

            $productionTimeRounded = round($productionTime, 2);
            $totalProductionTime += $productionTimeRounded;

            $lastProductType = $productType;
        }

        return new ProductionInfo($totalProductionTime, $productType, $lastProductType);
    }
}
