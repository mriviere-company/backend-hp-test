<?php

namespace App\Services;

use App\Models\ChangeoverDelay;

class ChangeoverDelayService
{
    public function getDelay(): int
    {
        $delay = ChangeoverDelay::first();
        return $delay ? $delay->delay_minutes : 0;
    }
}
