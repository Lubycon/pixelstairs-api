<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class MarketNameTranslate extends BaseModel
{
    use SoftDeletes;

    protected $table = 'markets_name_translate';

    protected $fillable = ['original','korean','chinese','english'];

    protected $casts = [
        'id' => 'string',
    ];
}
