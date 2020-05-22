<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded = ['id'];
    
    public function items() 
    {
        return $this->belongsToMany('App\Item')->withPivot('quantity');
    }
}
