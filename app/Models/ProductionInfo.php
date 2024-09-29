<?php

namespace App\Models;

class ProductionInfo
{
    public float $totalProductionTime;
    public string $productType;
    public ?string $lastProductType;

    public function __construct(float $totalProductionTime, string $productType, ?string $lastProductType)
    {
        $this->totalProductionTime = $totalProductionTime;
        $this->productType = $productType;
        $this->lastProductType = $lastProductType;
    }
}
