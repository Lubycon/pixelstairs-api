<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Abort;
use Log;

class Option extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        'product_id','sku','name_translate_id','price','stock','safe_stock','image_id','option_collection_id'
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

    public function canBuyAble(){
        if( $this->stock < 1 ) Abort::Error('0058','The sku have no stock');
    }


    public function product()
    {
        return $this->belongsTo('App\Models\Product','product_id','id');
    }

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
        return $this->hasOne('App\Models\OptionNameTranslate','id','name_translate_id');
    }
}
