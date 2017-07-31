<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SigndropAnswer extends Model 
{
    use SoftDeletes;
    public $timestamps = true;
    protected $dates = ['deleted_at'];
    protected $fillable = ['answer','signdrop_id'];

    public function getAnswer(){
        return [
            "kor" => $this->answer_korean,
            "eng" => $this->answer_english,
        ];
    }

    public function signdropQuestion()
    {
        return $this->belongsTo('App\Models\SigndropQuestion');
    }

    public function signdrop()
    {
        return $this->belongsToMany('App\Models\Signdrop');
    }

}