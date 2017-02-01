<?php

namespace App\Models;

use Log;

class ImageGroup extends BaseModel
{
    protected $fillable = [
        "id","model_name"
    ];

    public function getImages(){
        $result = [];
        $images = $this->image;
        foreach( $images as $value ){
            $result[] = [
                "url" => $value['url'],
                "index" => $value['index']
            ];
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
