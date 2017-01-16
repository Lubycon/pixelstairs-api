<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use SoftDeletes;

    protected $casts = [
        'id' => 'string',
        'integer' => 'string',
    ];
}
