<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Brand extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'original_name',
        'chinese_name',
        'english_name',
        'korean_name',
    ];

    protected $casts = [
        'id' => 'string',
    ];
}
