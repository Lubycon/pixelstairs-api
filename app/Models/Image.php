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
}
