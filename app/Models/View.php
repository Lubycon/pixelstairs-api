<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class View extends Model {

    protected $casts = [
        'id' => 'string',
    ];
	protected $fillable = array('user_id', 'content_id');

	public function user()
	{
		return $this->belongsTo('User');
	}

	public function content()
	{
		return $this->hasOne('App\Models\Content', 'content_id');
	}

}