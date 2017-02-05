<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Division extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        'id',
        "parent_id",
        'translate_name_id',
    ];

    protected $casts = [
        'id' => 'string',
        'parent_id' => 'string',
    ];


    // get reference data
    // hasOne('remote_table_column_name','local_column_name');

    public function translateName()
    {
        return $this->hasOne('App\Models\TranslateName','id','translate_name_id');
    }

    // get reference data
    // hasMany('remote_table_column_name','local_column_name');
    public function reviewQuestionKey()
    {
        return $this->hasMany('App\Models\ReviewQuestionKey','division_id','id');
    }

    // belongsTo
    // belongsTo('remote_table_column_name','local_column_name');

    public function product()
    {
        return $this->belongsTo('App\Models\Product','division_id','id');
    }
}
