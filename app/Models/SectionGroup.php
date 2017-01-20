<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Log;

class SectionGroup extends Model
{
    use SoftDeletes;

    // get reference data
    // hasOne('remote_table_column_name','local_column_name');

    public function category()
    {
        return $this->hasOne('App\Models\Category','id', $this->division()->value('parent_id') );
    }

    public function division()
    {
        return $this->hasOne('App\Models\Division','id','parent_id');
    }


    // belongsTo
    // belongsTo('remote_table_column_name','local_column_name');

//    public function product()
//    {
//        return $this->belongsTo('App\Models\Product','category_id','id');
//    }
//    public function division()
//    {
//        return $this->belongsTo('App\Models\Division','parent_id','id');
//    }
}
