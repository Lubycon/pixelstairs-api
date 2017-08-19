<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\Signdrop
 *
 * @property int $id
 * @property int $user_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SigndropAnswer[] $signdropAnswer
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Models\SigndropSurvey[] $signdropSurvey
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Signdrop whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Signdrop whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Signdrop whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Signdrop whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Signdrop whereUserId($value)
 * @mixin \Eloquent
 */
class Signdrop extends Model 
{
    use SoftDeletes;

    public $timestamps = true;
    protected $dates = ['deleted_at'];
    protected $fillable = ['user_id'];

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
    public function signdropSurvey()
    {
        return $this->hasMany('App\Models\SigndropSurvey','signdrop_id','id');
    }
    public function signdropAnswer()
    {
        return $this->hasMany('App\Models\SigndropAnswer','signdrop_id','id');
    }

}