<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Option extends BaseModel
{
    use SoftDeletes;

    protected $casts = [
        'id' => 'string',
        'product_id' => 'string',
        'sku' => 'string',
    ];

    // get reference data
    // hasOne('remote_table_column_name','local_column_name');

    public function optionCollection()
    {
        return $this->hasOne('App\Models\OptionCollection','id','option_collection_id');
    }
}
