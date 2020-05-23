<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Item;
use Illuminate\Support\Facades\Cache;

class ItemController extends Controller
{
    public function index() 
    {
        $items = Item::where('stock', '>', 0)->get();
        return response()->json($items);
    }

    public function show($item_id) 
    {
        $item = null;
        $cacheKey = 'item' . $item_id;
        if (Cache::has($cacheKey)) {
            $item = Cache::get($cacheKey);
        } else {
            $item = Item::find($item_id);
        }
        return response()->json($item);
    }
}
