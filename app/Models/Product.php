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
        'market_product_id' => 'string',
        'haitao_product_id' => 'string',
        'category_id' => 'string',
        'division_id' => 'string',
        'market_id' => 'string',
        'brand_id' => 'string',
        'seller_id' => 'string',
        'gender_id' => 'string',
        // find way sector id to string
        'weight' => 'string',
        'status_code' => 'string',
    ];

    // get reference data
    // hasOne('remote_table_column_name','local_column_name');

    public function category()
    {
        return $this->hasOne('App\Models\Category','id','category_id');
    }
    public function division()
    {
        return $this->hasOne('App\Models\Division','id','division_id');
    }
    public function section()
    {
//        return $this->hasOne('App\Models\SectionGroup','id','section_group_id');
    }
    public function sectionGroup()
    {
        return $this->hasOne('App\Models\SectionGroup','id','section_group_id');
    }
    public function market()
    {
        return $this->hasOne('App\Models\Market','id','market_id');
    }
    public function brand()
    {
        return $this->hasOne('App\Models\Brand','id','brand_id');
    }
    public function seller()
    {
        return $this->hasOne('App\Models\Seller','id','seller_id');
    }
    public function gender()
    {
        return $this->hasOne('App\Models\Gender','id','gender_id');
    }
    public function status()
    {
        return $this->hasOne('App\Models\Status','id','status_id');
    }
    // get reference data

    // get translate data
    public function translate_name()
    {
        return $this->hasOne('App\Models\TranslateName','id','translate_name_id');
    }
    public function translate_description()
    {
        return $this->hasOne('App\Models\TranslateDescription','id','translate_description_id');
    }
    // get translate data

//    public function sectors(){
//        $result = [];
//        for( $i=0 ; $i<3 ; $i++ ){
//            $value = $this['sector_id_'.$i];
//            if(!is_null($value)) $result[] = $value;
//        }
//        return $result;
//    }
//
//    public function sectorsDetail(){
//        $result = [];
//        for( $i=0 ; $i<3 ; $i++ ){
//            $value = $this['sector_id_'.$i];
//            if(!is_null($value)){
//                $sector = Sector::find($value);
//                $result[] = array(
//                    'origin' => $value,
//                    'origin' => $sector['original_name'],
//                    'zh' => $sector['chinese_name'],
//                );
//            }
//        }
//        return $result;
//    }
//
//    public function sectorsDetailZh(){
//        $result = [];
//        for( $i=0 ; $i<3 ; $i++ ){
//            $value = $this['sector_id_'.$i];
//            if(!is_null($value)){
//                $sector = Sector::find($value);
//                $result[] = $sector['chinese_name'];
//            }
//        }
//        return $result;
//    }
}
