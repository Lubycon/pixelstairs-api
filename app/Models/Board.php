<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    protected $guarded = array('group','name');

    public function post()
    {
        return $this->belongsTo('App\Models\Post','board_id','board');
    }
}
