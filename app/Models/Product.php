<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

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
}
