<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Log;

class FreeGiftGroup extends BaseModel
{
    use SoftDeletes;

    protected $casts = [
        'id' => 'string',
    ];

    protected $fillable = [
        "product_id","stock_per_each"
    ];

    public function createGroupObject($options){
        $optionModels = [];
        foreach( $options as $key => $value ){
            $optionModels[] = new FreeGift([
                "group_id" => $this->id,
                "option_id" => $value['id'],
                "stock" => $value['stock'],
            ]);
        }
        return $optionModels;
    }


    // get reference data
    // hasMany('remote_table_column_name','local_column_name');
    public function freeGift()
    {
        return $this->hasMany('App\Models\FreeGift','group_id','id');
    }
}
