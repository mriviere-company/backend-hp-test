<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Artisan;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Models\Order;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Artisan::call('db:seed', ['--class' => 'ProductSeeder']);
        Artisan::call('db:seed', ['--class' => 'ChangeoverDelaySeeder']);
        Artisan::call('db:seed', ['--class' => 'ProductionSpeedSeeder']);
    }

    #[Test]
    public function it_can_create_an_order()
    {
        $order = new Order();
        $order->need_by = now()->addDays(5);
        $order->save();

        $this->assertDatabaseHas('orders', [
            'id' => $order->id,
            'need_by' => $order->need_by->format('Y-m-d H:i:s'),
        ]);
    }
}
