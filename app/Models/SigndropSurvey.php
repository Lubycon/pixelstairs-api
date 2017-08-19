<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\SigndropSurvey
 *
 * @property int $id
 * @property int $signdrop_id
 * @property int $signdrop_answer_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\Signdrop[] $signdrop
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SigndropAnswer[] $signdropAnswer
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SigndropSurvey whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SigndropSurvey whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SigndropSurvey whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SigndropSurvey whereSigndropAnswerId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SigndropSurvey whereSigndropId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\SigndropSurvey whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class SigndropSurvey extends Model
{
    use SoftDeletes;

    public $timestamps = true;
    protected $dates = ['deleted_at'];
    protected $fillable = ['signdrop_id','signdrop_answer_id'];

    public function signdrop()
    {
        return $this->belongsToMany('App\Models\Signdrop');
    }

    public function signdropAnswer()
    {
        return $this->hasMany('App\Models\SigndropAnswer');
    }
}