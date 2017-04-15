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

	public function getCounts(){
        return [
            "like" => $this->like_count,
            "view" => $this->view_count,
        ];
    }
    public function getHashTags(){
        return json_decode($this->hash_tags);
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
		return $this->hasOne('App\Models\ImageGroup');
	}

}