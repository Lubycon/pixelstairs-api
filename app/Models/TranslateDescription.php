<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class TranslateDescription extends BaseModel
{
    use SoftDeletes;

    protected $fillable = ['original','korean','chinese','english'];
}
