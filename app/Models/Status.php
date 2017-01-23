<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Status extends BaseModel
{
    use SoftDeletes;

    protected $casts = [
        'id' => 'string',
        'code' => 'string',
    ];
}
