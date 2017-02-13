<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class FreeGift extends BaseModel
{
    use SoftDeletes;

    protected $casts = [
        'id' => 'string',
        'option_id' => 'string',
        'group_id' => 'string',
    ];

    protected $fillable = [
        'option_id','group_id','stock'
    ];
}
