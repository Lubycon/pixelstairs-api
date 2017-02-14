<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class FreeGift extends BaseModel
{
    use SoftDeletes;

    protected $casts = [
        'id' => 'string',
        'option_id' => 'string',
        'group_id' => 'string',
    ];

    protected $fillable = [
        'option_id','group_id','stock'
    ];

    public function option()
    {
        return $this->hasOne('App\Models\Option','id','option_id');
    }
    // get translate data
    public function translateName()
    {
        return $this->hasOne('App\Models\TranslateName','id','translate_name_id');
    }
}
