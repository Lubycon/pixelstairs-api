<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sku extends Model
{
    protected $fillable = [
        'market_id','product_id','sku','description'
    ];

    protected $casts = [
        'id' => 'string',
        'product_id' => 'string',
    ];
}
