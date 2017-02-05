<?php

namespace App\Models;

use Log;

class ImageGroup extends BaseModel
{
    protected $fillable = [
        "id","model_name"
    ];
    protected $casts = [
        "id" => 'string',
    ];

    public function getObjects(){
        $result = [];
        $images = $this->image;
        foreach( $images as $value ){
            $result[] = $value->getObject();
        }
        return $result;
    }

    // get reference data
    // hasMany('remote_table_column_name','local_column_name');
    public function image()
    {
        return $this->hasMany('App\Models\Image','image_group_id','id');
    }
}
