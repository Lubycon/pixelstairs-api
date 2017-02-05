<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Option extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        'product_id','sku','translate_name_id','price','stock','safe_stock','image_id','option_collection_id'
    ];

    protected $casts = [
        'id' => 'string',
        'product_id' => 'string',
        'sku' => 'string',
        'image_id' => 'string',
        'option_collection_id' => 'string',
    ];

    public static $absoluteSafeStock = 15;

    public static function absoluteSafeStockCkeck($value){
        return $value > Option::$absoluteSafeStock ? $value :Option::$absoluteSafeStock ;
    }

    // get reference data
    // hasOne('remote_table_column_name','local_column_name');

    public function optionCollection()
    {
        return $this->hasOne('App\Models\OptionCollection','id','option_collection_id');
    }

    public function image()
    {
        return $this->hasOne('App\Models\Image','id','image_id');
    }

    // get translate data
    public function translateName()
    {
        return $this->hasOne('App\Models\TranslateName','id','translate_name_id');
    }
}
