<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Interest extends BaseModel
{
    use SoftDeletes;

    protected $casts = [
        'id' => 'string',
        'user_id' => 'string',
        'category_id' => 'string',
        'division_id' => 'string',
        'section_id' => 'string',
    ];

    protected $fillable = [
        'user_id','category_id','division_id','section_id'
    ];
}
