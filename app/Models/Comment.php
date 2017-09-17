<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

/**
 * App\Models\Comment
 *
 * @property int $id
 * @property int $user_id
 * @property int $content_id
 * @property string $description
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @property-read \App\Models\Content $content
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Comment whereContentId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Comment whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Comment whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Comment whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Comment whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Comment whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Comment whereUserId($value)
 * @mixin \Eloquent
 */
class Comment extends Model {

	use SoftDeletes;

    protected $casts = [
        'id' => 'string',
        'user_id' => 'string',
        'content_id' => 'string',
    ];
	protected $dates = ['deleted_at'];
	protected $fillable = array('user_id', 'content_id', 'description');


	public function getCommentWithAuthor(){
	    return [
	        "id" => $this->id,
            "description" => $this->description,
            "writtenTime" => $this->created_at->format("Y-m-d H:i:s"),
            "user" => $this->user->getMyInfo(),
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
