<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class CategoryNameTranslate extends BaseModel
{
    use SoftDeletes;

    protected $table = 'categories_name_translate';

    protected $fillable = ['original','korean','chinese','english'];

    protected $casts = [
        'id' => 'string',
    ];
}
