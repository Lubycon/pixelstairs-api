<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class StatusNameTranslate extends BaseModel
{
    use SoftDeletes;

    protected $table = 'statuses_name_translate';

    protected $fillable = ['original','korean','chinese','english'];

    protected $casts = [
        'id' => 'string',
    ];
}
