<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Image extends Model {

	use SoftDeletes;

	protected $dates = ['deleted_at'];
	protected $fillable = array('url', 'index', 'is_pixel_own', 'image_group_id');

    public function getObject(){
        return [
            "id" => $this->id,
            "file" => $this->url,
            "index" => $this->index,
            "isPixelOwn" => $this->is_pixel_own,
            "deleted" => false
        ];
    }

	public function imageGroup()
	{
		return $this->belongsToMany('ImageGroup');
	}

}