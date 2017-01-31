<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Interest extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        'user_id','category_id','division_id','section_id'
    ];
}
