<?php

namespace App\Models;

class Image extends BaseModel
{
    protected $fillable = [
        'index',
        'url',
        'is_mitty_own',
        'image_group_id'
    ];

    protected $casts = [
        "id" => 'string',
        "index" => 'string',
        'image_group_id' => "string"
    ];

    public function getObject(){
        return [
            "id" => $this->id,
            "file" => $this->getUrl(),
            "index" => $this->index,
        ];
    }

    public function getUrl(){
        return $this->attributes['is_mitty_own'] ? env('S3_PATH').$this->url : $this->url;
    }
}
