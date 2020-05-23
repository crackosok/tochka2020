<?php 

namespace App\Services;

use App\Item;
use App\Order;
use App\Services\NotificationService;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;

class OrderService 
{
    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function makeOrder($validated) 
    {
        $order = Order::create(Arr::except($validated, ['items']));

        $orderItems = $validated['items'];
        $orderPrice = 0.0;
        foreach($orderItems as $orderItem) { 
            $order->items()->attach($orderItem['item_id'], ['quantity' => $orderItem['quantity'], 'item_price' => $orderItem['price']]);
            $item = Item::find($orderItem['item_id']);
            if ($orderItem['quantity'] < $item->stock) {
                $item->stock -= $orderItem['quantity'];
                $item->save();
                $orderPrice += $item->price * $orderItem['quantity'];
            } else {
                $order->items()->delete();
                $order->delete();
                Log::error('Attempt to order more items than there are in stock');
                return null;
            }
        }
        $order->price = $orderPrice;
        $order->save();
        Log::info("Order $order->id successfully created");

        $this->notificationService->newOrder($order);
        $this->postOrderIntegrations();

        return $order->id;
    }

    public function postOrderIntegrations() 
    {
        // send to delivery service, crm, etc
    }
}