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
}
