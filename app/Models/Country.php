<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
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
