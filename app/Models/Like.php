<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Like
 *
 * @property int $id
 * @property int $user_id
 * @property int $content_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @property-read \App\Models\Content $content
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\User[] $user
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Like whereContentId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Like whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Like whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Like whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Like whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Like whereUserId($value)
 * @mixin \Eloquent
 */
class Like extends Model {

	use SoftDeletes;

    protected $casts = [
        'id' => 'string',
    ];
	protected $dates = ['deleted_at'];
	protected $fillable = array('user_id', 'content_id');

	public function user()
	{
		return $this->belongsToMany('App\Models\User');
	}

	public function content()
	{
		return $this->belongsTo('App\Models\Content');
	}

}