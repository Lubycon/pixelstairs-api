<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class GiveProduct extends BaseModel
{
    use SoftDeletes;

    protected $casts = [
        'id' => 'string',
        'review_id' => 'string',
        'apply_user_id' => 'string',
        'accept_user_id' => 'string',
        'award_id' => 'string',
    ];
}
