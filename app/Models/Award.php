<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Award extends BaseModel
{
    use SoftDeletes;

    protected $casts = [
        'id' => 'string',
        'product_id' => 'string',
        'user_id' => 'string',
    ];


    // belongsTo
    // belongsTo('remote_table_column_name','local_column_name');

    public function product()
    {
        return $this->belongsTo('App\Models\Product','product_id','id');
    }
    public function option()
    {
        return $this->belongsTo('App\Models\Option','sku','sku');
    }
}
