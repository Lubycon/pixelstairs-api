<?php

namespace App\Models;

class ImageGroup extends BaseModel
{
    protected $fillable = [
        "id","review_id","product_id"
    ];
    // get reference data
    // hasMany('remote_table_column_name','local_column_name');
    public function image()
    {
        return $this->hasMany('App\Models\Image','image_group_id','id');
    }

}
