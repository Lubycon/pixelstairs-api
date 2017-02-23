<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class OptionNameTranslate extends BaseModel
{
    use SoftDeletes;

    protected $table = 'options_name_translate';

    protected $fillable = ['original','korean','chinese','english'];

    protected $casts = [
        'id' => 'string',
    ];
}
