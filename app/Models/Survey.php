<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Survey extends BaseModel
{
    use SoftDeletes;



    protected $casts = [
        'id' => 'string',
        'user_id' => 'string',
    ];

    // belongsTo
    // belongsTo('remote_table_column_name','local_column_name');

    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id','id');
    }
    public function interest()
    {
        return $this->hasOne('App\Models\Interest','id','interest_id');
    }

}
