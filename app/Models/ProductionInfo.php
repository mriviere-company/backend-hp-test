<?php

namespace App\Models;

class ProductionInfo
{
    public function __construct(
        public float $totalProductionTime,
        public string $currentProductType,
        public ?string $lastProductType
    )
    {
    }
}
