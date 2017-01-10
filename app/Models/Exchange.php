<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exchange extends Model
{
    protected $casts = [
        'id' => 'string',
    ];
    protected $dates = ['created_at','updated_at'];
}
