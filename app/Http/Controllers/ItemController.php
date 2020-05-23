<?php

namespace App\Http\Controllers;

use App\Services\ItemService;

class ItemController extends Controller
{
    public function __construct(ItemService $itemService)
    {
        $this->itemService = $itemService;    
    }

    public function index() 
    {
        $listItems = $this->itemService->listItems();
        if ($listItems->count()) {
            $response = ['success' => true, 'data' => $listItems];
            return response()->json($response, 200);
        } else {
            $response = ['success' => false, 'error' => [
                'code' => '404',
                'message' => 'No items in stock'
                ]
            ];
            return response()->json($response, 404);
        }
    }

    public function show($item_id) 
    {
        $item = $this->itemService->getItem($item_id);
        if ($item) {
            $response = ['success' => true, 'data' => $item];
            return response()->json($response, 200);
        } else {
            $response = ['success' => false, 'error' => [
                'code' => '404',
                'message' => 'No item found with such ID'
                ]
            ];
            return response()->json($response, 404);
        }
    }
}
