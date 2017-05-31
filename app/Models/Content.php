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
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Comment[] $comments
 * @property-read \App\Models\ImageGroup $imageGroup
 * @property-read \App\Models\License $license
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Like[] $likes
 * @property-read \App\Models\User $user
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\View[] $views
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

    public function viewIt(User $user){
        if( !$this->amIView($user) ){
            $this->views()->create(["user_id" => $user->id]);
            $this->view_count++;
            return $this->save();
        }
        return true;
    }
	public function likeIt(User $user){
	    if( !$this->amILike($user) ){
            $this->likes()->create(["user_id" => $user->id]);
            $this->like_count++;
            return $this->save();
        }
        return true;
    }
    public function dislikeIt(User $user){
        $this->likes()->whereuser_id($user->id)->delete();
        $this->like_count--;
        return $this->save();
    }
    public function amILike($user){
        if( !is_null($user) ){
            $result = $this->likes()
                ->whereuser_id($user->id)
                ->first();
            return !is_null($result);
        }
        return false;
    }
    public function amIView($user){
        if( !is_null($user) ) {
            $result = $this->views()
                ->whereuser_id($user->id)
                ->where('created_at','<',Carbon::now()->addSeconds(View::getCountUpLimitTime()))
                ->first();
            return !is_null($result);
        }
        return false;
    }

	public function getContentInfoWithAuthor($user){
        return [
            "id" => $this->id,
            "title" => $this->title,
            "description" => $this->description,
//            "thumbnailImg" => $this->getThumbnailImageObject(),
            "image" => $this->getGroupImageObject(),
            "licenseCode" => $this->license_code,
            "myLike" => $this->amILike($user),
            "counts" => $this->getCounts(),
            "hashTags" => $this->getHashTags(),
            "user" => $this->user->getSimpleInfo(),
			"createdAt" => Carbon::parse($this->created_at)->toDateTimeString(),
			"updatedAt" => Carbon::parse($this->updated_at)->toDateTimeString()
        ];
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

    public function getThumbnailImageObject(){
        $imageModel = $this->thumbnailImage;
        return $this->getImageObject($imageModel);
    }
    public function getGroupImageObject(){
        $imageModel = $this->imageGroup;
        $imageObjects = $this->getImageObject($imageModel);
        return count($imageObjects) > 1
            ? $imageObjects
            : $imageObjects[0];
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
//    public function thumbnailImage()
//    {
//        return $this->hasOne('App\Models\Image','id','thumbnail_image_id');
//    }
	public function imageGroup()
	{
		return $this->hasOne('App\Models\ImageGroup','id','image_group_id');
	}
    public function likes()
    {
        return $this->hasMany('App\Models\Like','content_id','id');
    }
    public function views()
    {
        return $this->hasMany('App\Models\View','content_id','id');
    }
    public function comments()
    {
        return $this->hasMany('App\Models\Comment','content_id','id');
    }

}
