<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Division extends Model
{
    use SoftDeletes;

    protected $fillable = [
        "parent_id",
        "original_name",
        "chinese_name"
    ];

    protected $casts = [
        'id' => 'string',
        'parent_id' => 'string',
    ];

}
