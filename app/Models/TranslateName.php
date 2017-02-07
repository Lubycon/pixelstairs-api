<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class TranslateName extends BaseModel
{
    use SoftDeletes;

    protected $fillable = ['original','korean','chinese','english'];

    protected $casts = [
        'id' => 'string',
    ];
}