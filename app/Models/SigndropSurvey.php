<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SigndropSurvey extends Model
{
    use SoftDeletes;

    public $timestamps = true;
    protected $dates = ['deleted_at'];
    protected $fillable = ['signdrop_id','signdrop_answer_id'];

    public function signdrop()
    {
        return $this->belongsToMany('App\Models\Signdrop');
    }

    public function signdropAnswer()
    {
        return $this->hasMany('App\Models\SigndropAnswer');
    }
}