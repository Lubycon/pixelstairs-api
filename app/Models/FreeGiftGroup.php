<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class FreeGiftGroup extends BaseModel
{
    use SoftDeletes;

    protected $casts = [
        'id' => 'string',
    ];

    // get reference data
    // hasMany('remote_table_column_name','local_column_name');
    public function freeGift()
    {
        return $this->hasMany('App\Models\FreeGift','group_id','id');
    }
}
