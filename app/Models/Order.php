<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use SoftDeletes;

    protected $casts = [
        'id' => 'string',
        'haitao_user_id' => 'string',
        'haitao_order_id' => 'string',
        'sku' => 'string',
        'quantity' => 'string',
        'status_code' => 'string',
    ];
}
