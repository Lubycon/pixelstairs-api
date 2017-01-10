<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    protected $fillable = [
        "parent_id",
        "market_id",
        "market_category_id",
        "name"
    ];

    protected $casts = [
        'id' => 'string',
        'parent_id' => 'string',
        'market_category_id' => 'string',
    ];

    protected $dates = ['created_at','updated_at'];
}
