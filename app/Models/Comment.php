<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends Model {

	use SoftDeletes;

	protected $dates = ['deleted_at'];
	protected $fillable = array('user_id', 'content_id', 'description');

	public function content()
	{
		return $this->belongsTo('App\Models\Content', 'content_id');
	}

	public function user()
	{
		return $this->belongsTo('App\Models\User', 'user_id');
	}

}