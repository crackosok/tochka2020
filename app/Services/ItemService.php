<?php 

namespace App\Services;

use App\Item;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class ItemService 
{
    public function listItems()
    {
        Log::info('Listing items in stock');
        return Item::inStock()->get();
    }

    public function getItem($item_id)
    {
        $cacheKey = 'item' . $item_id;
        if (Cache::has($cacheKey)) {
            $item = Cache::get($cacheKey);
            Log::info("Getting item $item_id from cache");
        } else {
            $item = Item::find($item_id);
            if ($item) {
                Cache::put($cacheKey, $item, 3600 * 24);
                Log::info("Getting item $item_id from db, cached for 24 hours");
            } else {
                Log::notice("Tried to get item $item_id, not found");
            }
        }
        return $item;
    }
}