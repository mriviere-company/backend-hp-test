<?php

namespace App\Services;

use App\Models\Order;
use App\Models\ProductionInfo;

interface ProductionTimeCalculatorInterface
{
    public function calculate(Order $order, int $delayMinutes, ?string &$lastProductType): ProductionInfo;
}
