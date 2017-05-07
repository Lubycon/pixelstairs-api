<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;
use Log;
use Abort;

class PasswordReset extends Model {
    protected static $expireTime = 30; //minutes

    public static function getByToken($token){
        $passwordReset = PasswordReset::wheretoken($token)->firstOrFail();
        return $passwordReset;
    }
    public static function getByEmail($token){
        $passwordReset = PasswordReset::whereemail($token)->firstOrFail();
        return $passwordReset;
    }
    public static function getUserByToken($token){
        return PasswordReset::getByToken($token)->user;
    }
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