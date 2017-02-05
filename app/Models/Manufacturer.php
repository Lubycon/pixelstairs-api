<?php

namespace App\Models;

class Manufacturer extends BaseModel
{

    protected $casts = [
        'id' => 'string',
        'country_id' => 'string',
    ];

    protected $fillable = [
        'country_id'
    ];

    // get reference data
    // hasOne('remote_table_column_name','local_column_name');

    public function translateName()
    {
        return $this->hasOne('App\Models\TranslateName','id','translate_name_id');
    }


    // belongsTo
    // belongsTo('remote_table_column_name','local_column_name');

    public function country()
    {
        return $this->belongsTo('App\Models\Country','country_id','id');
    }
}
