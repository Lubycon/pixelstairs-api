<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Content extends Model {

	protected $table = 'contents';
	public $timestamps = true;

	use SoftDeletes;

	protected $dates = ['deleted_at'];
	protected $fillable = array('user_id', 'licence_code',"thumbnail_image_id", 'image_group_id', 'title', 'description', 'view_count', 'like_count', 'hash_tags');

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
        $result = $this->likes()
            ->whereuser_id($user->id)
            ->first();
        return !is_null($result);
    }
    public function amIView($user){
        $result = $this->views()
            ->whereuser_id($user->id)
            ->first();
        return !is_null($result);
    }

	public function getContentInfoWithAuthor(){
        return [
            "id" => $this->id,
            "title" => $this->title,
            "description" => $this->description,
            "thumbnailImg" => $this->getThumbnailImageObject(),
            "images" => $this->getGroupImageObject(),
            "licenseCode" => $this->licence_code,
            "myLike" => "",
            "counts" => $this->getCounts(),
            "hashTags" => $this->getHashTags(),
            "user" => $this->user->getSimpleInfo(),
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
        return $this->getImageObject($imageModel);
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
    public function thumbnailImage()
    {
        return $this->hasOne('App\Models\Image','id','thumbnail_image_id');
    }
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