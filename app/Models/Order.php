<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends BaseModel
{
    use SoftDeletes;

    protected $casts = [
        'id' => 'string',
        'product_id' => 'string',
        'haitao_user_id' => 'string',
        'haitao_order_id' => 'string',
        'sku' => 'string',
        'order_status_code' => 'string',
    ];


    // belongsTo
    // belongsTo('remote_table_column_name','local_column_name');

    public function product()
    {
        return $this->belongsTo('App\Models\Product','product_id','id');
    }
    public function option()
    {
        return $this->belongsTo('App\Models\Option','sku','sku');
    }
    public function status()
    {
        return $this->hasOne('App\Models\Status','code','order_status_code');
    }
}
