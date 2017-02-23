<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Section extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        "id",
        "parent_id",
        "name_translate_id",
    ];

    protected $casts = [
        'id' => 'string',
        'parent_id' => 'string',
    ];


    // get reference data
    // hasOne('remote_table_column_name','local_column_name');

    public function sectionMarketInfo()
    {
        return $this->hasOne('App\Models\SectionMarketInfo','section_id','id');
    }

    // belongsTo
    // belongsTo('remote_table_column_name','local_column_name');

    public function sectionGroup()
    {
        return $this->belongsTo('App\Models\SectionGroup','group_id','id');
    }

    // get translate data
    public function translateName()
    {
        return $this->hasOne('App\Models\SectionNameTranslate','id','name_translate_id');
    }


}
