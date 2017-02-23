<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class ReviewQuestionDescriptionTranslate extends BaseModel
{
    use SoftDeletes;

    protected $table = 'review_questions_description_translate';

    protected $fillable = ['original','korean','chinese','english'];

    protected $casts = [
        'id' => 'string',
    ];
}
