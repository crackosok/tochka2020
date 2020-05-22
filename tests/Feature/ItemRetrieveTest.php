<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ItemRetrieveTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Get list of items that are in stock.
     *
     * @return void
     */
    public function testShowItemsList()
    {
        $items = factory(\App\Item::class, 10)->create();

        $this->get('/api/items')
            ->assertStatus(200)
            ->assertJson($items->where('stock', '>', 0)->toArray());
    }

    /**
     * Get single item information.
     *
     * @return void
     */
    public function testShowItemInfo() 
    {
        $item = factory(\App\Item::class)->create();
        
        $this->get("/api/items/{$item->id}")
            ->assertStatus(200)
            ->assertJson($item->toArray());
    }
}
