<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class View extends Model
{
    public function giveUser()
    {
        return $this->hasOne('App\Models\User','id','give_user_id');
    }

    public function takeUser()
    {
        return $this->hasOne('App\Models\User','id','take_user_id');
    }

    public function board()
    {
        return $this->hasOne('App\Models\Board','board_id','board');
    }

    public function post()
    {
        return $this->hasOne('App\Models\Post','id','post_id');
    }
}
