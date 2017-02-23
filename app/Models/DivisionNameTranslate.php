<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class DivisionNameTranslate extends BaseModel
{
    use SoftDeletes;

    protected $table = 'divisions_name_translate';

    protected $fillable = ['original','korean','chinese','english'];

    protected $casts = [
        'id' => 'string',
    ];
}
