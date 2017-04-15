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

}