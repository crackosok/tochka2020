<?php

namespace App\Http\Controllers;

use App\Item;
use App\Notifications\OrderClient;
use App\Notifications\OrderManager;
use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Notification;
use App\Services\OrderService;

class OrderController extends Controller
{
    public function create(Request $request) 
    {
        $order_id = OrderService::makeOrder($request);
        return response()->json(['success' => true, 'order_id' => $order->id]);
    }
}
