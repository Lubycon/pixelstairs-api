<?php

namespace App\Models;

class Seller extends BaseModel
{
    protected $fillable = [
        'id',
        'name',
        "rate",
    ];

    protected $casts = [
        'id' => 'string',
        'rate' => 'string',
    ];

    // get translate data
    public function translateName()
    {
        return $this->hasOne('App\Models\TranslateName','id','translate_name_id');
    }
}
