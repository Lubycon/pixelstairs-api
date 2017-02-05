<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class OptionCollection extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        'option_key_id_0','option_key_id_1','option_key_id_2','option_key_id_3'
    ];

    protected $casts = [
        'id' => 'string',
        'option_key_id_0' => 'string',
        'option_key_id_1' => 'string',
        'option_key_id_2' => 'string',
        'option_key_id_3' => 'string',
    ];


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
