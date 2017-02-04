<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class ReviewQuestionKey extends BaseModel
{
    use SoftDeletes;


    public function translateName()
    {
        return $this->hasOne('App\Models\TranslateName','id','translate_name_id');
    }
}
