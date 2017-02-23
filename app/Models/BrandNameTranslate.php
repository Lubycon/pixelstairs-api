<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class BrandNameTranslate extends BaseModel
{
    use SoftDeletes;

    protected $table = 'brands_name_translate';

    protected $fillable = ['original','korean','chinese','english'];

    protected $casts = [
        'id' => 'string',
    ];
}
