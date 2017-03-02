<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class ReviewQuestionKey extends BaseModel
{
    use SoftDeletes;

    protected $casts = [
        'id' => 'string',
        'division_id' => 'string',
    ];


    public function translateName()
    {
        return $this->hasOne('App\Models\TranslateName','id','translate_name_id');
    }
}
