<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Tracker
 *
 * @property int $id
 * @property string $uuid
 * @property string $current_url
 * @property string $prev_url
 * @property int $action
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Tracker whereAction($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Tracker whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Tracker whereCurrentUrl($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Tracker whereId($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Tracker wherePrevUrl($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Tracker whereUpdatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\Tracker whereUuid($value)
 * @mixin \Eloquent
 */
class Tracker extends Model {

	protected $fillable = array('uuid', 'current_url', 'prev_url', 'action');
    protected $casts = [
        'id' => 'string',
    ];

}