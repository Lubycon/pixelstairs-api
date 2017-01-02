<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContentTag extends Model
{
    protected $fillable = ['post_id','name'];
}
