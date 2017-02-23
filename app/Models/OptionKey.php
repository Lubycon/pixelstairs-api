<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class OptionKey extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        'name_translate_id'
    ];

    protected $casts = [
        'id' => 'string',
    ];

    // get translate data
    public function translateName()
    {
        return $this->hasOne('App\Models\OptionKeyNameTranslate','id','name_translate_id');
    }
}
