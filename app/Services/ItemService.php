<?php 

namespace App\Services;

use App\Item;
use Illuminate\Support\Facades\Cache;

class ItemService 
{
    public function listItems()
    {
        return Item::inStock()->get();
    }

    public function getItem($item_id)
    {
        $cacheKey = 'item' . $item_id;
        if (Cache::has($cacheKey)) {
            $item = Cache::get($cacheKey);
        } else {
            $item = Item::find($item_id);
        }
        return $item;
    }
}