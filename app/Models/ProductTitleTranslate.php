<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class ProductTitleTranslate extends BaseModel
{
    use SoftDeletes;

    protected $table = 'products_title_translate';

    protected $fillable = ['original','korean','chinese','english'];

    protected $casts = [
        'id' => 'string',
    ];
}
