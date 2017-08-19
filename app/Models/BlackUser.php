<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\BlackUser
 *
 * @property string $id
 * @property string $user_id
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property string $deleted_at
 * @property-read \App\Models\User $user
 * @method static \Illuminate\Database\Query\Builder|\App\Models\BlackUser whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\BlackUser whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\BlackUser whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\BlackUser whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\BlackUser whereUserId($value)
 * @mixin \Eloquent
 */
class BlackUser extends Model {
    use SoftDeletes;

    protected $casts = [
        'id' => 'string',
        'user_id' => 'string'
    ];

    protected $fillable = ['user_id'];

    public function user() {
        return $this->hasOne('App\Models\User', 'id', 'user_id');
    }
}
