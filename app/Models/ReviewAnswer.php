<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class ReviewAnswer extends BaseModel
{
    use SoftDeletes;

    protected $casts = [
        'id' => 'string',
        'review_id' => 'string',
        'question_id' => 'string',
    ];

    protected $fillable = [
        'question_id',
        'review_id',
        'description',
        'score',
    ];

    public function question()
    {
        return $this->belongsTo('App\Models\ReviewQuestion','question_id','id');
    }
}
