<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tracker extends Model {

	protected $fillable = array('uuid', 'current_url', 'prev_url', 'action');
    protected $casts = [
        'id' => 'string',
    ];

}