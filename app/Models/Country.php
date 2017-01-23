<?php

namespace App\Models;


class Country extends BaseModel
{
    protected $guarded = array('utc','region','continent','name');

    protected $casts = [
        'id' => 'string',
    ];

    public function user()
    {
        // return $this->belongsTo('App\Models\User','id','id');
    }
}
