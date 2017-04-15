<?php

namespace App\Models;

use Illuminate\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Foundation\Auth\Access\Authorizable;
use Illuminate\Contracts\Auth\Authenticatable as AuthenticatableContract;
use Illuminate\Contracts\Auth\Access\Authorizable as AuthorizableContract;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Log;
use Illuminate\Support\Str;
use Request;
use Abort;

class User extends Model implements AuthenticatableContract,
    AuthorizableContract,
    CanResetPasswordContract
{
    use Authenticatable, Authorizable, CanResetPassword ,SoftDeletes;

	protected $dates = ['deleted_at'];
    protected $hidden = ['password', 'token'];
	protected $fillable = ['email', 'password', 'nickname', 'image_id', 'newsletters_accepted', 'terms_of_service_accepted'];



    public static function bindSigninData($request){
        return [
            "email" => $request->email,
            "password" => $request->password
        ];
    }
	public static function bindSignupData($request){
	    return [
	        "email" => $request->email,
            "password" => bcrypt($request->password),
            "nickname" => $request->nickname,
            "newsletters_accepted" => $request->newsletterAccepted,
            "terms_of_service_accepted" => $request->termsOfServiceAccepted,
            "grade" => "general",
            "status" => "inactive",
        ];
    }


    public static function getFromEmail($email){
	    return User::whereemail($email)->firstOrFail();
    }

    public static function getAccessUser(){
	    try{
            $accessToken = Request::header("x-pixel-token");
            $userId = substr($accessToken, 31);
            return User::findOrFail($userId)->wheretoken($accessToken)->firstOrFail();
        }catch(\Exception $e){
	        Abort::Error('0043','Token dose not match');
        }
    }

    public function insertAccessToken(){
        // TODO : device info add to token
//	    $deviceInfo = Request::header("x-pixel-device");
        $userId = $this->id;
        $device = 'w';
        $randomStr = Str::random(30);
        $token = $device.$randomStr.$userId; //need change first src from header device check

        $this->token = $token;
        $this->save();
        return $token;
    }
    public function dropToken(){
        $this->token = null;
        $this->save();
    }
    public function createSignupToken(){
        $recoded = SignupAllow::whereemail($this->email);
        if(!is_null($recoded)) $recoded->delete();
        SignupAllow::create([
            "email" => $this->email,
            "token" =>  Str::random(50)
        ]);
    }


    public function getImageObject(){
        $imageModel = $this->image;
        $result = is_null($imageModel)
            ? null
            : $imageModel->getObject();
        return $result;
    }
    public function getSignupToken(){
        $signupAllowModel = $this->signupAllow;
        $result = is_null($signupAllowModel)
            ? Abort::Error('0040','This user has not signup token')
            : $signupAllowModel->token;
        return $result;
    }
    public function getSignupDiffTime(){
        $signupAllowModel = $this->signupAllow;
        return $signupAllowModel->getDiffTime();
    }
    public function checkSignupCode($code){
        $userSignupToken = $this->getSignupToken();
        $this->signupAllow->expiredCheck();
        return $userSignupToken === $code;
    }


    public function image()
	{
		return $this->hasOne('App\Models\Image','id','image_id');
	}
    public function signupAllow()
    {
        return $this->hasOne('App\Models\SignupAllow','email','email');
    }
    public function contents()
    {
        return $this->hasMany('App\Models\Content','user_id','id');
    }
}



//public static function insertRememberToken($id){
//    $user = User::findOrFail($id);
//
//    $userId = $user->id;
//    $device = 'w';
//    $randomStr = Str::random(30);
//    $token = $device.$randomStr.$userId; //need change first src from header device kind
//
//    $user->remember_token = $token;
//    $user->save();
//
//    return $token;
//}

//public static function insertSignupToken($id){
//    $user = User::findOrFail($id);
//
//    $recoded = SignupAllow::where('email', $user->email);
//
//    if(!is_null($recoded)){
//        $recoded->delete();
//    }
//    $signup = new SignupAllow;
//    $signup->id = $user->id;
//    $signup->email = $user->email;
//    $signup->token = Str::random(50);
//    $signup->save();
//}
//
//public static function checkToken($request){
//    $token = $request->header('X-mitty-token');
//    $tokenData = (object)array(
//        "device" => substr($token, 0, 1),
//        "token" => substr($token, 1, 30),
//        "id" => substr($token, 31),
//    );
//    return $tokenData;
//}
//
//public static function checkUserExistById($id){
//    $user = User::find($id);
//    if (!is_null($user)) {
//        return true;
//    }
//    return false;
//}
//
//public static function checkUserExistByIdOnlyTrashed($id)
//{
//    $user = User::onlyTrashed()->find($id);
//    if (!is_null($user)) {
//        return true;
//    }
//    return false;
//}
//
//public static function checkUserExistByEmail($data){
//    $user = User::whereRaw("email = '".$data['email']."' and sns_code = ".$data['snsCode'])->get();
//    if(!$user->isempty()) {
//        return true;
//    }
//    return false;
//}