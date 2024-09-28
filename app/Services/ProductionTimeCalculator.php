<?php

namespace App\Services;

use App\Models\ProductionInfo;
use App\Models\ProductionSpeed;
use App\Models\Order;

class ProductionTimeCalculator implements ProductionTimeCalculatorInterface
{
    public function calculate(Order $order, int $delayMinutes, ?string &$lastProductType): ProductionInfo
    {
        $productionSpeeds = ProductionSpeed::all()->keyBy('product_type');
        $totalProductionTime = 0;
        $currentProductType = null;

        foreach ($order->orderItems as $orderItem) {
            $currentProductType = $orderItem->product->type;
            $quantity = $orderItem->quantity;

            if (isset($productionSpeeds[$currentProductType]) && $quantity >= 1) {
                $unitsPerHour = $productionSpeeds[$currentProductType]->units_per_hour;
                $unitsPerMinute = $unitsPerHour / 60;
                $productionTime = $quantity / $unitsPerMinute;

                if ($lastProductType && $lastProductType !== $currentProductType) {
                    $totalProductionTime += $delayMinutes;
                }

                $totalProductionTime += round($productionTime, 2);
                $lastProductType = $currentProductType;
            }
        }

        return new ProductionInfo($totalProductionTime, $currentProductType, $lastProductType);
    }
}
