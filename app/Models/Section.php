<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Section extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        "id",
        "group_id",
        "translate_name_id",
    ];

    protected $casts = [
        'id' => 'string',
        'group_id' => 'string',
    ];


    // get translate data
    public function translateName()
    {
        return $this->hasOne('App\Models\TranslateName','id','translate_name_id');
    }
}
