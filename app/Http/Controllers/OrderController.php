<?php

namespace App\Http\Controllers;

use App\Http\Requests\OrderRequest;
use Illuminate\Http\Request;
use App\Services\OrderService;

class OrderController extends Controller
{
    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;    
    }

    public function create(OrderRequest $request) 
    {
        $validated = $request->validated();
        $order_id = $this->orderService->makeOrder($validated);
        if ($order_id) {
            $response = ['success' => true, 'data' => ['order_id' => $order_id]];
            return response()->json($response, 200);
        } else {
            $response = ['success' => false, 'error' => [
                'code' => '422',
                'message' => 'You are trying to order more than we have in stock'
                ]
            ];
            return response()->json($response, 422);
        }
    }
}
