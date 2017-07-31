<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Signdrop extends Model 
{
    use SoftDeletes;

    public $timestamps = true;
    protected $dates = ['deleted_at'];
    protected $fillable = ['user_id'];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function signdropSurvey()
    {
        return $this->hasMany('App\Models\SigndropSurvey','signdrop_id','id');
    }
    public function signdropAnswer()
    {
        return $this->hasMany('App\Models\SigndropAnswer','signdrop_id','id');
    }

}