<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\SigndropQuestion
 *
 * @property int $id
 * @property string $question_korean
 * @property string $question_english
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SigndropAnswer[] $signdropAnswer
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SigndropQuestion whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SigndropQuestion whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SigndropQuestion whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SigndropQuestion whereQuestionEnglish($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SigndropQuestion whereQuestionKorean($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SigndropQuestion whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SigndropQuestion extends Model
{
    use SoftDeletes;

    public $timestamps = true;
    protected $dates = ['deleted_at'];
    protected $fillable = ['question'];

    public function getQuestion(){
        return [
            "ko" => $this->question_korean,
            "en" => $this->question_english,
        ];
    }

    public function signdropAnswer()
    {
        return $this->hasMany('App\Models\SigndropAnswer');
    }
}
