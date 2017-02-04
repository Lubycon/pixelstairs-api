<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class ReviewQuestionKey extends BaseModel
{
    use SoftDeletes;


    public function translateDescription()
    {
        return $this->hasOne('App\Models\TranslateDescription','id','translate_description_id');
    }
}
