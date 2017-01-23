<?php

namespace App\Models;

class Manufacturer extends BaseModel
{


    protected $fillable = [
        'translate_name_id'
    ];

    // get reference data
    // hasOne('remote_table_column_name','local_column_name');

    public function translateName()
    {
        return $this->hasOne('App\Models\TranslateName','id','translate_name_id');
    }
}
