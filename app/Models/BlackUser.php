<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

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
