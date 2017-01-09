<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MarketCategory extends Model
{
    public function division()
    {
        return $this->hasMany('App\Models\Division','category_id','id');
    }
}
