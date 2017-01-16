<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Log;
use App\Models\Sector;

class Product extends Model
{
    use SoftDeletes;

    public function option()
    {
        return $this->hasMany('App\Models\Option','product_id','id');
    }

    protected $casts = [
        'id' => 'string',
        'product_id' => 'string',
        'haitao_product_id' => 'string',
        'category_id' => 'string',
        'division_id' => 'string',
        'sector_id_0' => 'string',
        'sector_id_1' => 'string',
        'sector_id_2' => 'string',
        'brand_id' => 'string',
    ];

    public function sectors(){
        $result = [];
        for( $i=0 ; $i<3 ; $i++ ){
            $value = $this['sector_id_'.$i];
            if(!is_null($value)) $result[] = $value;
        }
        return $result;
    }

    public function sectorsDetail(){
        $result = [];
        for( $i=0 ; $i<3 ; $i++ ){
            $value = $this['sector_id_'.$i];
            if(!is_null($value)){
                $sector = Sector::find($value);
                $result[] = array(
                    'origin' => $value,
                    'origin' => $sector['original_name'],
                    'zh' => $sector['chinese_name'],
                );
            }
        }
        return $result;
    }
}
