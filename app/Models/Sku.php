<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sku extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'market_id','product_id','sku','description'
    ];

    protected $casts = [
        'id' => 'string',
        'product_id' => 'string',
    ];
}
