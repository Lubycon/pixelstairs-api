<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Log;

class SectionGroup extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        'parent_id',
        'section_id_0',
        'section_id_1',
        'section_id_2',
    ];

    protected $casts = [
        'id' => 'string',
        'parent_id' => 'string',
        'section_id_0' => 'string',
        'section_id_1' => 'string',
        'section_id_2' => 'string',
    ];

    // get reference data
    // hasOne('remote_table_column_name','local_column_name');

    public function sectionById($int)
    {
        return $this->hasOne('App\Models\Section','id','section_id_'.$int);
    }

    // belongsTo
    // belongsTo('remote_table_column_name','local_column_name');
    public function division()
    {
        return $this->belongsTo('App\Models\Division','parent_id','id');
    }


    // get translate data
    public function translateName()
    {
        return $this->hasOne('App\Models\TranslateName','id','translate_name_id');
    }
}
