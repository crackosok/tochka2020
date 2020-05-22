<?php

namespace App\Http\Controllers;

use App\Item;
use App\Order;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function getItems() 
    {
        $items = Item::where('stock', '>', 0)->get();
        return response()->json($items);
    }

    public function getItem(Item $item) 
    {
        return response()->json($item);
    }

    public function makeOrder(Request $request) 
    {
        $order = Order::create($request->except('items'));
        $orderItems = $request->items;
        $orderPrice = 0.0;
        foreach($orderItems as $orderItem) { 
            $order->items()->attach($orderItem['item_id'], ['quantity' => $orderItem['quantity']]);
            $item = Item::find($orderItem['item_id']);
            $item->stock -= $orderItem['quantity'];
            $item->save();
            $orderPrice += $item->price * $orderItem['quantity'];
        }
        $order->price = $orderPrice;
        $order->save();
        return response()->json(['success' => true, 'order_id' => $order->id]);
    }
}
