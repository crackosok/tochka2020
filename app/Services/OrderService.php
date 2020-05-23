<?php 

namespace App\Services;

use App\Item;
use App\Notifications\OrderClient;
use App\Notifications\OrderManager;
use App\Order;
use Illuminate\Support\Facades\Notification;

class OrderService {
    public static function makeOrder($request) {
        $order = Order::create($request->except('items'));
        $orderItems = $request->items;
        $orderPrice = 0.0;
        foreach($orderItems as $orderItem) { 
            $order->items()->attach($orderItem['item_id'], ['quantity' => $orderItem['quantity'], 'item_price' => $orderItem['price']]);
            $item = Item::find($orderItem['item_id']);
            $item->stock -= $orderItem['quantity'];
            $item->save();
            $orderPrice += $item->price * $orderItem['quantity'];
        }
        $order->price = $orderPrice;
        $order->save();
        Notification::route('mail', $request->client_email)->notify(new OrderClient($orderPrice));
        Notification::route('mail', config('notifications.email.managers'))->notify(new OrderManager($orderPrice));
    }
}