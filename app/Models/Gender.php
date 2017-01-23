<?php

namespace App\Models;

class Gender extends BaseModel
{
    protected $fillable = [
        'id',
    ];

    protected $casts = [
        'id' => 'string',
    ];
}
