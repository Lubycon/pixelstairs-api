<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    protected $casts = [
        'id' => 'string',
        'product_id' => 'string',
        'sku_id' => 'string',
    ];
}
