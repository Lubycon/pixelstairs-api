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

    protected $casts = [
        'id' => 'string',
        'image_id' => 'string',
    ];
	protected $dates = ['deleted_at'];
    protected $hidden = ['password', 'token'];
	protected $fillable = ['email', 'password', 'nickname', 'image_id', 'newsletters_accepted', 'terms_of_service_accepted'];

    /**
     *  @SWG\Definition(
     *   definition="signin",
     *   type="object",
     *   allOf={
     *       @SWG\Schema(
     *           required={"email","password"},
     *           @SWG\Property(property="email", type="string", default="test@pixelstairs.com"),
     *           @SWG\Property(property="password", type="string", default="password"),
     *       )
     *   }
     * )
     */
    public static function bindSigninData($request){
        return [
            "email" => $request->email,
            "password" => $request->password
        ];
    }
    /**
     *  @SWG\Definition(
     *   definition="signup",
     *   type="object",
     *   allOf={
     *       @SWG\Schema(
     *           required={"email","password","nickname","newsletterAccepted","termsOfServiceAccepted"},
     *           @SWG\Property(property="email", type="string", default="test@pixelstairs.com"),
     *           @SWG\Property(property="password", type="string", default="password"),
     *           @SWG\Property(property="nickname", type="string", default="usernick"),
     *           @SWG\Property(property="newsletterAccepted", type="boolean"),
     *           @SWG\Property(property="termsOfServiceAccepted", type="boolean"),
     *       )
     *   }
     * )
     */
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

    public static function isMyId($user_id){
        if( User::getAccessUser()->id === $user_id )return true;
        return Abort::Error('0043','It is not target user id');
    }
    public static function isMyContent($content_id){
        if( User::getAccessUser()->id === Content::findOrFail($content_id)->user_id )return true;
        return Abort::Error('0043','It is user own');
    }
    public static function isMyComment($comment_id){
        if( User::getAccessUser()->id === Comment::findOrFail($comment_id)->user_id )return true;
        return Abort::Error('0043','It is user own');
    }


    public static function isGhost(){
        return User::getAccessToken() === null;
    }
    public static function isUser(){
        User::getAccessUser();
        return true;
    }
    public static function isAdmin(){
        // TODO :: admin
        return false;
    }


    public static function getFromEmail($email){
	    return User::whereemail($email)->firstOrFail();
    }

    public static function getAccessUser(){
        try{
            $userInfo = User::getUserInfo();
            return User::findOrFail($userInfo->user_id)
                ->wheretoken($userInfo->access_token)
                ->firstOrFail();
        }catch(\Exception $e){
            Abort::Error('0043','Token dose not match');
        }
    }
    public static function getAccessUserOrNot(){
        try{
            $userInfo = User::getUserInfo();
            return User::findOrFail($userInfo->user_id)
                ->wheretoken($userInfo->access_token)
                ->firstOrFail();
        }catch(\Exception $e){
            return null;
        }
    }

    public static function getUserInfo(){
        $accessToken = User::getAccessToken();
        $userId = substr($accessToken, 31);
        return (object)[
            "user_id" => $userId,
            "access_token" => $accessToken,
        ];
    }

    public static function getAccessToken(){
        try{
            return Request::header("x-pixel-token");
        }catch(\Exception $e){
            return null;
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


    public function getSimpleInfo(){
        return [
            "id" => $this->id,
            "email" => $this->email,
            "nickname" => $this->nickname,
            "profileImg" => $this->getImageObject(),
        ];
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