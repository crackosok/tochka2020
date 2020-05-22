<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Making a correct order.
     *
     * @return void
     */
    public function testMakeOrder()
    {
        $items = factory(\App\Item::class, 3)->create();
        $data = [
            'customer_name' => 'Kirill',
            'customer_email' => 'me@crackos.ru',
            'customer_phone' => '+79022784717',
            'items' => [
                ['item_id' => 1, 'quantity' => 2],
                ['item_id' => 2, 'quantity' => 1],
                ['item_id' => 3, 'quantity' => 3],
            ]
        ];
        $this->postJson('/api/orders', $data)
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'order_id' => 1
            ]);

    }
}
