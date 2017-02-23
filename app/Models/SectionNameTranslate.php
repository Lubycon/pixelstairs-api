<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class SectionNameTranslate extends BaseModel
{
    use SoftDeletes;

    protected $table = 'sections_name_translate';

    protected $fillable = ['original','korean','chinese','english'];

    protected $casts = [
        'id' => 'string',
    ];
}
