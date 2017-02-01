<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends BaseModel
{
    use SoftDeletes;



    // belongsTo
    // belongsTo('remote_table_column_name','local_column_name');

    public function product()
    {
        return $this->belongsTo('App\Models\Product','product_id','id');
    }
    public function user()
    {
        return $this->belongsTo('App\Models\Product','user_id','id');
    }
    public function option()
    {
        return $this->belongsTo('App\Models\Option','sku','sku');
    }


    // get reference data
    // hasMany('remote_table_column_name','local_column_name');
    public function answer()
    {
        return $this->hasMany('App\Models\ReviewAnswer','review_id','id');
    }
}
