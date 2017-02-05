<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class ReviewQuestion extends BaseModel
{
    use SoftDeletes;

    protected $casts = [
        'id' => 'string',
        'question_key_id' => 'string',
        'division_id' => 'string',
    ];

    // get reference data
    // hasOne('remote_table_column_name','local_column_name');

    public function translateDescription()
    {
        return $this->hasOne('App\Models\TranslateDescription','id','translate_description_id');
    }

    public function questionKey()
    {
        return $this->hasOne('App\Models\ReviewQuestionKey','id','question_key_id');
    }

    // get reference data
    // hasMany('remote_table_column_name','local_column_name');
    public function answer()
    {
        return $this->hasMany('App\Models\ReviewAnswer','review_id','id');
    }
}
