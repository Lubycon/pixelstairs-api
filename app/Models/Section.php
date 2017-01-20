<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Section extends Model
{
    use SoftDeletes;

    protected $fillable = [
        "id",
        "group_id",
        "translate_name_id",
    ];

    protected $casts = [
        'id' => 'string',
        'group_id' => 'string',
    ];
}
