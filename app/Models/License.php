<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * App\Models\License
 *
 * @property string $code
 * @property string $description
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @property \Carbon\Carbon $deleted_at
 * @method static \Illuminate\Database\Query\Builder|\App\Models\License whereCode($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\License whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\License whereDeletedAt($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\License whereDescription($value)
 * @method static \Illuminate\Database\Query\Builder|\App\Models\License whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class License extends Model {

	use SoftDeletes;

    protected $casts = [
        'id' => 'string',
    ];
	protected $dates = ['deleted_at'];
	protected $fillable = array('code', 'description');

}