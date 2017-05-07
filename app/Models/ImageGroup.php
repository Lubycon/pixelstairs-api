<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ImageGroup extends Model {

	use SoftDeletes;

    protected $casts = [
        'id' => 'string',
    ];
	protected $dates = ['deleted_at'];

	public function getObject(){
	    $result = [];
        foreach($this->images as $value){
            $result[] = $value->getObject();
        }
        return $result;
    }

    public function images()
    {
        return $this->hasMany('App\Models\Image','image_group_id','id');
    }
}