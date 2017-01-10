<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function option()
    {
        return $this->hasMany('App\Models\Option','product_id','id');
    }

    protected $casts = [
        'id' => 'string',
        'product_id' => 'string',
        'haitao_product_id' => 'string',
        'category_id' => 'string',
        'division_id' => 'string',
        'brand_id' => 'string',
    ];

    protected $dates = ['created_at','updated_at'];
}
