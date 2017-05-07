<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Log;
use Abort;

/**
 * App\Models\SignupAllow
 *
 * @property int $id
 * @property string $email
 * @property string $token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SignupAllow whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SignupAllow whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SignupAllow whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SignupAllow whereToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SignupAllow whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SignupAllow extends Model {

    protected static $expireTime = 30; //minutes
    protected $fillable = [
        'email','token'
    ];

    public function expiredCheck(){
        if( $this->getDiffTime() ){
            return true;
        }else{
            Abort::Error('0040','Over Expired time');
        }
    }
    public function getDiffTime(){
        $nowTime = Carbon::now();
        $expiredTime = $this->created_at->addMinutes($this::$expireTime);
        if($nowTime > $expiredTime) return 0;
        return $nowTime->diffInSeconds($expiredTime);
    }

	public function user()
	{
		return $this->belongsTo('App\Models\User', 'email', 'email');
	}

}