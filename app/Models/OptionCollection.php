<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class OptionCollection extends BaseModel
{
    use SoftDeletes;


    // get reference data
    // hasOne('remote_table_column_name','local_column_name');

    public function optionKey($int)
    {
        return $this->hasOne('App\Models\OptionKey','id','option_key_id_'.$int);
    }
    // get translate data
    public function translateName()
    {
        return $this->hasOne('App\Models\TranslateName','id','translate_name_id');
    }
}
