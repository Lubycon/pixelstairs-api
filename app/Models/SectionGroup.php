<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Log;

class SectionGroup extends BaseModel
{
    use SoftDeletes;

    // get reference data
    // hasOne('remote_table_column_name','local_column_name');

    public function section()
    {
        return $this->hasMany('App\Models\Section','group_id','id');
    }


    // belongsTo
    // belongsTo('remote_table_column_name','local_column_name');

//    public function product()
//    {
//        return $this->belongsTo('App\Models\Product','category_id','id');
//    }
    public function division()
    {
        return $this->belongsTo('App\Models\Division','parent_id','id');
    }
}
