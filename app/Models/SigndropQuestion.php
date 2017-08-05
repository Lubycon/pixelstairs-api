<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SigndropQuestion extends Model
{
    use SoftDeletes;

    public $timestamps = true;
    protected $dates = ['deleted_at'];
    protected $fillable = ['question'];

    public function getQuestion(){
        return [
            "ko" => $this->question_korean,
            "en" => $this->question_english,
        ];
    }

    public function signdropAnswer()
    {
        return $this->hasMany('App\Models\SigndropAnswer');
    }
}
