<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\SigndropAnswer
 *
 * @property int $id
 * @property int $signdrop_question_id
 * @property string $answer_korean
 * @property string $answer_english
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Signdrop[] $signdrop
 * @property-read \App\Models\SigndropQuestion $signdropQuestion
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SigndropAnswer whereAnswerEnglish($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SigndropAnswer whereAnswerKorean($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SigndropAnswer whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SigndropAnswer whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SigndropAnswer whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SigndropAnswer whereSigndropQuestionId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SigndropAnswer whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SigndropAnswer extends Model
{
    use SoftDeletes;
    public $timestamps = true;
    protected $dates = ['deleted_at'];
    protected $fillable = ['answer', 'signdrop_id'];

    public function getAnswer()
    {
        return [
            "id"  => $this->id,
            "ko" => $this->answer_korean,
            "en" => $this->answer_english,
        ];
    }

    public function signdropQuestion()
    {
        return $this->belongsTo('App\Models\SigndropQuestion');
    }

    public function signdrop()
    {
        return $this->belongsToMany('App\Models\Signdrop');
    }

}
