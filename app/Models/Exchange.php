<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Exchange extends BaseModel
{
    use SoftDeletes;

    protected $casts = [
        'id' => 'string',
    ];
}
