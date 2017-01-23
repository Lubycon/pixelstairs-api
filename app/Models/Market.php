<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Market extends BaseModel
{
    use SoftDeletes;

    protected $casts = [
        'id' => 'string',
        'code' => 'string',
        'country_id' => 'string',
    ];

    // get reference data
    // hasOne('remote_table_column_name','local_column_name');

    public function country()
    {
        return $this->hasOne('App\Models\Country','id','country_id');
    }
    public function translateName()
    {
        return $this->hasOne('App\Models\TranslateName','id','translate_name_id');
    }


    // belongsTo
    // belongsTo('remote_table_column_name','local_column_name');

    public function product()
    {
        return $this->belongsTo('App\Models\Product','market_id','code');
    }
    public function section_info()
    {
        return $this->belongsTo('App\Models\SectionMarketInfo','market_id','code');
    }
}
