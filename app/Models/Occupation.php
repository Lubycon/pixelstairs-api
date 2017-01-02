<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Occupation extends Model
{
    protected $guarded = array('name');

    public function user()
    {
        return $this->belongsTo('App\User','id','id');
    }
}
