<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends BaseModel
{
    use SoftDeletes;
    
    protected $fillable = [
        "user_id",
        "order_status_code",
        "recipient_name",
        "recipient_phone",
        "country_id",
        "state",
        "city",
        "address1",
        "address2",
        "post_code",
        "market_id",
        "product_id",
        "product_option_id",
        "product_price",
        "product_currency",
        "product_quantity",
        "product_weight",
        "product_url",
        "product_total_price",
        "domestic_delivery_price",
        "domestic_delivery_currency",
        "international_delivery_price",
        "international_delivery_currency",
        "from_currency_amount",
        "from_currency",
        "to_currency_amount",
        "to_currency",
        "payment_company",
        "payment_id",
        "payment_create_time",
        "payment_price",
        "payment_currency",
        "payment_state",
    ];

    protected $casts = [
        'id' => 'string',
        'product_id' => 'string',
        'haitao_user_id' => 'string',
        'haitao_order_id' => 'string',
        'option_id' => 'string',
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
        return $this->belongsTo('App\Models\Option','option_id','id');
    }
    public function status()
    {
        return $this->hasOne('App\Models\Status','code','order_status_code');
    }
}
