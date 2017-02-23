<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class GenderNameTranslate extends BaseModel
{
    use SoftDeletes;

    protected $table = 'genders_name_translate';

    protected $fillable = ['original','korean','chinese','english'];

    protected $casts = [
        'id' => 'string',
    ];
}
