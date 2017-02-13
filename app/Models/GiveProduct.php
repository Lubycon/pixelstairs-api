<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class GiveProduct extends BaseModel
{
    use SoftDeletes;

    protected $casts = [
        'id' => 'string',
        'review_id' => 'string',
        'apply_user_id' => 'string',
        'accept_user_id' => 'string',
        'award_id' => 'string',
    ];

    protected $fillable = [
        'review_id','apply_user_id','accept_user_id','give_status_code','award_id'
    ];


    public function review()
    {
        return $this->belongsTo('App\Models\Review','review_id','id');
    }
}
