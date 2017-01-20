<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Division extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'id',
        "parent_id",
    ];

    protected $casts = [
        'id' => 'string',
        'parent_id' => 'string',
    ];


    // get reference data
    // hasOne('remote_table_column_name','local_column_name');

    public function translate_name()
    {
        return $this->hasOne('App\Models\TranslateName','id','translate_name_id');
    }


    // belongsTo
    // belongsTo('remote_table_column_name','local_column_name');

    public function product()
    {
        return $this->belongsTo('App\Models\Product','division_id','id');
    }
}
