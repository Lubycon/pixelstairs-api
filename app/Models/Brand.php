<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    protected $fillable = [
        'name'
    ];

    protected $casts = [
        'id' => 'string',
    ];

    protected $dates = ['created_at','updated_at'];
}
