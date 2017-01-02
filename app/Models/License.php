<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class License extends Model
{
    protected $guarded = array('code','url','icon','description');

    public function content()
    {
        return $this->belongsTo('App\Models\Content','license_id','id');
    }
}
