<?php

namespace Tests\Feature;

use App\Notifications\OrderClient;
use App\Notifications\OrderManager;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Notifications\AnonymousNotifiable;
use Tests\TestCase;
use Illuminate\Support\Facades\Notification;

class OrderTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Making a correct order.
     *
     * @return void
     */
    public function testSuccessfulOrder()
    {
        Notification::fake();
        $items = factory(\App\Item::class, 3)->create();
        $data = [
            'customer_name' => 'Kirill',
            'customer_email' => 'me@crackos.ru',
            'customer_phone' => '79022784717',
            'items' => [
                ['item_id' => 1, 'quantity' => 2, 'price' => $items->find(1)->price],
                ['item_id' => 2, 'quantity' => 1, 'price' => $items->find(2)->price],
                ['item_id' => 3, 'quantity' => 3, 'price' => $items->find(3)->price],
            ]
        ];
        $this->postJson('/api/orders', $data)->dump()
            ->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'order_id' => 1
                ]
            ]);
        Notification::assertSentTo(
            new AnonymousNotifiable, OrderClient::class
        );
        Notification::assertSentTo(
            new AnonymousNotifiable, OrderManager::class
        );
}

    /**
     * Making an incorrect order
     * 
     * @return void
     */
    public function testIncorrectOrder() 
    {
        $items = factory(\App\Item::class, 3)->create();
        $data = [
            'customer_name' => 'Kirill',
            'customer_email' => 'me@crackos.ru',
            'customer_phone' => '79022784717',
            'items' => [
                ['item_id' => 1, 'quantity' => $items->find(1)->stock + 1, 'price' => $items->find(1)->price],
                ['item_id' => 2, 'quantity' => 1, 'price' => $items->find(2)->price],
                ['item_id' => 3, 'quantity' => 3, 'price' => $items->find(3)->price],
            ]
        ];
        $this->postJson('/api/orders', $data)
            ->assertStatus(422)
            ->assertJson([
                'success' => false,
                'error' => [
                    'code' => '422',
                    'message' => 'You are trying to order more than we have in stock'
                ]
            ]);
    }
}
