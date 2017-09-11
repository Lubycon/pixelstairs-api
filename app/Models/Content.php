<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;
use Log;

use App\Models\View;

/**
 * App\Models\Content
 *
 * @property int $id
 * @property int $user_id
 * @property string $license_code
 * @property int $image_group_id
 * @property string $title
 * @property string $description
 * @property int $view_count
 * @property int $like_count
 * @property string $hash_tags
 * @property \Carbon\Carbon $deleted_at
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comment[] $comment
 * @property-read \App\Models\ImageGroup $imageGroup
 * @property-read \App\Models\License $license
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Like[] $like
 * @property-read \App\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\View[] $view
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Content whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Content whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Content whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Content whereHashTags($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Content whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Content whereImageGroupId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Content whereLicenseCode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Content whereLikeCount($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Content whereTitle($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Content whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Content whereUserId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Content whereViewCount($value)
 * @mixin \Eloquent
 */
class Content extends Model {

	use SoftDeletes;

    protected $casts = [
        'id' => 'string',
        'user_id' => 'string',
        'thumbnail_image_id' => 'string',
        'image_group_id' => 'string',
    ];
	protected $dates = ['deleted_at'];
	protected $fillable = array('user_id', 'license_code', 'image_group_id', 'title', 'description', 'view_count', 'like_count', 'hash_tags');

    public function viewIt($user){
        if( !$this->amIView($user) ){
            $this->view()->create([
                "user_id" => is_null($user) ? null : $user->id,
                "ip" => app('request')->clientInfo['ip'],
            ]);
            $this->view_count++;
            return $this->save();
        }
        return true;
    }
	public function likeIt(User $user){
	    if( !$this->amILike($user) ){
            $this->like()->create(["user_id" => $user->id]);
            $this->like_count++;
            return $this->save();
        }
        return true;
    }
    public function dislikeIt(User $user){
        if( $this->amILike($user) ){
            $this->like()->where('user_id',$user->id)->delete();
            $this->like_count--;
            return $this->save();
        }
    }
    public function amILike($user){
        if( !is_null($user) ){
            foreach($this->like as $value){
                if( $user->id === (string)$value['user_id'] ) return true;
            }
        }
        return false;
    }
    public function amIView($user){
        // option 1. check same ip
        //        2. check on limit time
        //        3. check if auth that user id
        $result = $this->view()
            ->where('ip',app('request')->clientInfo['ip'])
            ->where('created_at','>',Carbon::now()->subSeconds(View::getCountUpLimitTime()));
        if( !is_null($user) ) $result->whereuser_id($user->id);
        return !is_null($result->first());
    }

	public function getContentInfoWithAuthor($user){
        return [
            "id" => $this->id,
            "title" => $this->title,
            "description" => $this->description,
            "image" => $this->getGroupImageObject(),
            "licenseCode" => $this->license_code,
            "myLike" => $this->amILike($user),
            "counts" => $this->getCounts(),
            "hashTags" => $this->getHashTags(),
            "user" => is_null($this->user)
                ? User::getDropInfo()
                : $this->user->getSimpleInfo(),
			"createdAt" => Carbon::parse($this->created_at)->toDateTimeString(),
			"updatedAt" => Carbon::parse($this->updated_at)->toDateTimeString()
        ];
    }
	public function getContentInfoWithAuthorByAdmin() {
		// Here is no 'myLike' prop, and 'deletedAt' prop is added
		$contentInfo = [
			"id" => $this->id,
			"title" => $this->title,
			"description" => $this->description,
            "image" => $this->getGroupImageObject(),
            "licenseCode" => $this->license_code,
            "counts" => $this->getCounts(),
            "hashTags" => $this->getHashTags(),
            "user" => $this->user->getSimpleInfo(),
			"createdAt" => Carbon::parse($this->created_at)->toDateTimeString(),
			"updatedAt" => Carbon::parse($this->updated_at)->toDateTimeString(),
			"deletedAt" => is_null($this->deleted_at)
                ? null
                : Carbon::parse($this->deleted_at)->toDateTimeString(),
		];

		return $contentInfo;
	}

	public function getCounts(){
        return [
            "like" => $this->like_count,
            "view" => $this->view_count,
        ];
    }
    public function getHashTags(){
        return json_decode($this->hash_tags);
    }
    public function getGroupImageObject(){
        if( is_null($this->imageGroup) ) return null;
        return $this->imageGroup->getFirstObject();
    }
    public function getImageObject($imageModel){
        $result = is_null($imageModel)
            ? null
            : $imageModel->getObject();
        return $result;
    }


	public function user()
	{
		return $this->belongsTo('App\Models\User', 'user_id', 'id');
	}
	public function license()
	{
		return $this->belongsTo('App\Models\License', 'code', 'license_code');
	}
	public function imageGroup()
	{
		return $this->hasOne('App\Models\ImageGroup','id','image_group_id');
	}
    public function like()
    {
        return $this->hasMany('App\Models\Like','content_id','id');
    }
    public function view()
    {
        return $this->hasMany('App\Models\View','content_id','id');
    }
    public function comment()
    {
        return $this->hasMany('App\Models\Comment','content_id','id');
    }

}
