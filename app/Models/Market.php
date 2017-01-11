<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Market extends Model
{
    protected $casts = [
        'id' => 'string',
        'country_id' => 'string',
    ];
}
