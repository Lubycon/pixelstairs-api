<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Award extends BaseModel
{
    use SoftDeletes;

    protected $casts = [
        'id' => 'string',
        'product_id' => 'string',
        'option_id' => 'string',
        'user_id' => 'string',
    ];

    protected $fillable = [
        'product_id','option_id','user_id','target','is_review_written','give_stock','expire_date'
    ];


    // belongsTo
    // belongsTo('remote_table_column_name','local_column_name');

    public function user()
    {
        return $this->belongsTo('App\Models\User','user_id','id');
    }
    public function product()
    {
        return $this->belongsTo('App\Models\Product','product_id','id');
    }
    public function option()
    {
        return $this->belongsTo('App\Models\Option','option_id','id');
    }
}
