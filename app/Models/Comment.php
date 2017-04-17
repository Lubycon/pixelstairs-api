<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class Comment extends Model {

	use SoftDeletes;

	protected $dates = ['deleted_at'];
	protected $fillable = array('user_id', 'content_id', 'description');


	public function getCommentWithAuthor(){
	    return [
	        "id" => $this->id,
            "description" => $this->description,
            "writtenTime" => $this->created_at->format("Y-m-d H:i:s"),
            "user" => $this->user->getSimpleInfo(),
        ];
    }

	public function content()
	{
		return $this->belongsTo('App\Models\Content', 'content_id');
	}

	public function user()
	{
        return $this->belongsTo('App\Models\User', 'user_id', 'id');
	}

}