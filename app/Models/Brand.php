<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        'id','name_translate_id'
    ];

    protected $casts = [
        'id' => 'string',
    ];

    // get reference data
    // hasOne('remote_table_column_name','local_column_name');

    public function translateName()
    {
        return $this->hasOne('App\Models\BrandNameTranslate','id','name_translate_id');
    }


    // belongsTo
    // belongsTo('remote_table_column_name','local_column_name');

    public function product()
    {
        return $this->belongsTo('App\Models\Product','brand_id','id');
    }
}
