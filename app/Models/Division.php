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
}
