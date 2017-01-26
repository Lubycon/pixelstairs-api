<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class OptionKey extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        'translate_name_id'
    ];

    // get translate data
    public function translateName()
    {
        return $this->hasOne('App\Models\TranslateName','id','translate_name_id');
    }
}
