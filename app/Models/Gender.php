<?php

namespace App\Models;

class Gender extends BaseModel
{
    protected $fillable = [
        'id',
    ];

    protected $casts = [
        'id' => 'string',
    ];



    // get reference data
    // hasOne('remote_table_column_name','local_column_name');

    public function translateName()
    {
        return $this->hasOne('App\Models\TranslateName','id','translate_name_id');
    }
}
