<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class ReviewQuestion extends BaseModel
{
    use SoftDeletes;

    // get reference data
    // hasMany('remote_table_column_name','local_column_name');
    public function answer()
    {
        return $this->hasMany('App\Models\ReviewAnswer','review_id','id');
    }
}
