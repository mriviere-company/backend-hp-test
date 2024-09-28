<?php

namespace App\Repositories;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;

class OrderRepository implements OrderRepositoryInterface
{
    public function getOrdersDueAfter(Carbon $date): Collection
    {
        return Order::with(['orderItems.product'])
            ->where('need_by', '>', $date)
            ->get();
    }
}
