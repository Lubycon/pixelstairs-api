<?php

namespace App\Models;

class Seller extends BaseModel
{
    protected $fillable = [
        'id',
        'translate_name_id',
        "rate",
    ];

    protected $casts = [
        'id' => 'string',
        'rate' => 'string',
    ];
}
