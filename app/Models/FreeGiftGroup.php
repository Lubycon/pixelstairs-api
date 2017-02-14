<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Log;
use Abort;

class FreeGiftGroup extends BaseModel
{
    use SoftDeletes;

    protected $casts = [
        'id' => 'string',
    ];

    protected $fillable = [
        "product_id","stock_per_each","first_deploy_count"
    ];

    public function createGroupObject($options){
        $optionModels = [];
        foreach( $options as $key => $value ){
            $this->optionValidate($value['id']);
            $optionModels[] = new FreeGift([
                "group_id" => $this->id,
                "option_id" => $value['id'],
                "stock" => $value['stock'],
            ]);
        }
        return $optionModels;
    }

    public function optionValidate($option_id){
        $product = Product::findOrFail($this->product_id);
        if( is_null($product->option->find($option_id)) ){
            $product->freeGiftGroup()->delete();
            Abort::Error('0040','Unknown Product Option Id');
        }
    }


    // get reference data
    // hasMany('remote_table_column_name','local_column_name');
    public function freeGift()
    {
        return $this->hasMany('App\Models\FreeGift','group_id','id');
    }
}
