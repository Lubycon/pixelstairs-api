<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class ProductDescriptionTranslate extends BaseModel
{
    use SoftDeletes;

    protected $table = 'products_description_translate';

    protected $fillable = ['original','korean','chinese','english'];

    protected $casts = [
        'id' => 'string',
    ];
}
