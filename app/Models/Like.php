<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Like extends Model {

	use SoftDeletes;

	protected $dates = ['deleted_at'];
	protected $fillable = array('user_id', 'content_id');

	public function user()
	{
		return $this->belongsToMany('User');
	}

	public function content()
	{
		return $this->belongsTo('Content');
	}

}