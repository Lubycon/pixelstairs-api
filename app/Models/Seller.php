<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seller extends Model
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
