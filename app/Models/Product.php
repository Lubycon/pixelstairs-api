<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function option()
    {
        return $this->hasMany('App\Models\Option','product_id','id');
    }
}
