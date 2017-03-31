<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SignupAllow extends Model {

    protected $fillable = [
        'email','token'
    ];


	public function user()
	{
		return $this->belongsTo('User', 'email', 'email');
	}

}