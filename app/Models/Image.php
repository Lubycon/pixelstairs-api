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
        'image_group_id' => "string"
    ];

    public function getObject(){
        return [
            "id" => $this->id,
            "file" => $this->url,
            "index" => $this->index,
            "deleted" => false
        ];
    }
}
