<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Status extends BaseModel
{
    use SoftDeletes;

    protected $casts = [
        'id' => 'string',
        'code' => 'string',
    ];

    public function translateName()
    {
        return $this->hasOne('App\Models\TranslateName','id','translate_name_id');
    }
}
