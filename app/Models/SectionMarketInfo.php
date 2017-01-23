<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class SectionMarketInfo extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        'section_id',
        'market_id',
        'market_category_id',
    ];


    // get translate data
    public function translateName()
    {
        return $this->hasOne('App\Models\TranslateName','id','translate_name_id');
    }
}
