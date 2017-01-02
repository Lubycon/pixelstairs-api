<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    // 1 : 1
    public function board()
    {
        return $this->hasOne('App\Models\Board','id','board_id');
    }
    public function user()
    {
        return $this->hasOne('App\Models\User','id','user_id');
    }
    public function lisence()
    {
        return $this->hasOne('App\Models\License','id','user_id');
    }
    public function category()
    {
        return $this->hasOne('App\Models\ContentsCategory','id','user_id');
    }

    // 1 : n
    public function view()
    {
        return $this->hasMany('App\Models\View','post_id','id');
    }
    public function comment()
    {
        return $this->hasMany('App\Models\Comment','post_id','id');
    }
}
