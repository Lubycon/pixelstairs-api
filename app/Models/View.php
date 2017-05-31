<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\View
 *
 * @property int $id
 * @property int $user_id
 * @property int $content_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Models\Content $content
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Query\Builder|\App\Models\View whereContentId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\View whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\View whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\View whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\View whereUserId($value)
 * @mixin \Eloquent
 */
class View extends Model {

    protected $casts = [
        'id' => 'string',
    ];
	protected $fillable = array('user_id','ip','content_id');

	protected static $countUpLimitTime = 60; //how seconds set limit

    public static function getCountUpLimitTime(){
        return self::$countUpLimitTime;
    }

	public function user()
	{
		return $this->belongsTo('App\Models\User');
	}

	public function content()
	{
		return $this->hasOne('App\Models\Content', 'content_id');
	}

}