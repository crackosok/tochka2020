<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    public $timestamps = false;

    public function orders() 
    {
        return $this->belongsToMany('App\Order')->withPivot('quantity', 'item_price');
    }

    public function scopeInStock($query) {
        return $query->where('stock', '>', 0);
    }
}
