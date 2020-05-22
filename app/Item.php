<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    public $timestamps = false;

    public function orders() 
    {
        return $this->belongsToMany('App\Order')->withPivot('quantity');
    }
}
