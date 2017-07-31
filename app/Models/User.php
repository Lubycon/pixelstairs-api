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
use Carbon\Carbon;

/**
 * App\Models\User
 *
 * @property int $id
 * @property string $email
 * @property string $password
 * @property string $nickname
 * @property bool $newsletters_accepted
 * @property bool $terms_of_service_accepted
 * @property string $gender
 * @property string $grade
 * @property string $status
 * @property int $image_id
 * @property string $token
 * @property string $last_login_time
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Content[] $contents
 * @property-read \App\Models\Image $image
 * @property-read \App\Models\SignupAllow $signupAllow
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereGender($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereGrade($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereImageId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereLastLoginTime($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereNewslettersAccepted($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereNickname($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereStatus($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereTermsOfServiceAccepted($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereToken($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string $birthday
 * @method static \Illuminate\Database\Query\Builder|\App\Models\User whereBirthday($value)
 */
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
	protected $fillable = ['email', 'password', 'nickname', 'image_id','birthday','gender','grade','status', 'newsletters_accepted', 'terms_of_service_accepted'];

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
            "birthday" => Carbon::parse($request->birthday)->timezone(config('app.timezone'))->toDatetimeString(),
            "grade" => "general",
            "status" => "inactive",
        ];
    }
    public static function bindSignupDataByAdmin($request){
        return [
            "email" => $request->email,
            "password" => bcrypt($request->password),
            "nickname" => $request->nickname,
            "newsletters_accepted" => $request->newsletterAccepted,
            "terms_of_service_accepted" => $request->termsOfServiceAccepted,
            "birthday" => Carbon::parse($request->birthday)->timezone(config('app.timezone'))->toDatetimeString(),
            "grade" => $request->grade,
            "status" => $request->status,
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
        $userGrade = User::getAccessUser()->grade;
        return $userGrade === 'admin' || $userGrade === 'super_admin';
    }


    public static function getFromEmail($email){
        return User::whereemail($email)->firstOrFail();
    }

    public static function getFromNickname($nickname){
        return User::wherenickname($nickname)->firstOrFail();
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

    public function insertAccessToken($token = null){
        $device = app('request')->clientInfo['device']['typeCode'];
        if( is_null($token) ){
            $userId = $this->id;
            $deviceCode = $device;
            $randomStr = Str::random(30);
            $token = $deviceCode.$randomStr.$userId; //need change first src from header device check
        }

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
            "gender" => $this->gender,
            "birthday" => $this->birthday,
            "status" => $this->status
        ];
    }
    public function getDetailInfo(){
        return [
            "id" => $this->id,
            "email" => $this->email,
            "nickname" => $this->nickname,
            "profileImg" => $this->getImageObject(),
            "gender" => $this->gender,
            "birthday" => $this->birthday,
            "status" => $this->status
        ];
    }
    public function getDetailInfoByAdmin() {
        return [
            "id" => $this->id,
            "email" => $this->email,
            "nickname" => $this->nickname,
            "profileImg" => $this->getImageObject(),
            "gender" => $this->gender,
            "birthday" => $this->birthday,
            "grade" => $this->grade,
            "status" => $this->status,
            "newsletterAccepted" => $this->newsletters_accepted,

            "createdAt" => Carbon::parse($this->created_at)->timezone(config('app.timezone'))->toDatetimeString(),
            "updatedAt" => Carbon::parse($this->updated_at)->timezone(config('app.timezone'))->toDatetimeString(),
            "lastLoginTime" => $this->last_login_time,

            "isBlackUser" => $this->isBlackUser()
        ];
    }
    public function getBlackInfo() {
        return [
            "createdAt" => Carbon::parse($this->blackUser->created_at)->timezone(config('app.timezone'))->toDatetimeString(),
            "updatedAt" => Carbon::parse($this->blackUser->updated_at)->timezone(config('app.timezone'))->toDatetimeString(),
            "deletedAt" => Carbon::parse($this->blackUser->deleted_at)->timezone(config('app.timezone'))->toDatetimeString(),
        ];
    }

    public function setToBlackList() {
        if(!$this->isBlackUser()) {
            $this->blackUser()->create([
                "user_id" => $this->id
            ]);

            return $this;
        }
    }

    public function removeFromBlackList() {
        if($this->isBlackUser()) {
            $this->blackUser()->delete();

            return $this;
        }
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

    private function isBlackUser() {
        return $this->blackUser()->exists();
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
    public function blackUser()
    {
        return $this->hasOne('App\Models\BlackUser','user_id','id');
    }
    public function signdrop()
    {
        return $this->hasOne('App\Models\Signdrop','user_id','id');
    }
}
