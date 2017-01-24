<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Log;
use App\Models\section;

class Product extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        'translate_name_id','translate_description_id'
    ];

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
        // find way section id to string
        'weight' => 'string',
        'status_code' => 'string',
    ];


    // get information data
    public function getPriceInfo(){
        return [
            "price" => $this->original_price,
            "lowestPrice" => $this->lower_price,
            "unit" => $this->unit,
        ];
    }
    public function getSectionIds(){
        $sections = $this->sectionGroup->section;
        $result = [];
        foreach( $sections as $key => $value ){
            $result[] = $value['id'];
        }
        return $result;
    }
    public function getOptionKey(){
        $result = [];
        if( count($this->option) ) {
            $optionKeys = $this->option->first()->optionCollection;
            for ($i = 0; $i < 4; $i++) {
                if (is_null($optionKeys['option_key_id_' . $i])) return $result;
                $result[] = $this->getTranslate( $optionKeys->optionKey($i)->first() );
            }
        }
        return $result;
    }
    public function getOption(){
        $result = [];
        if( count($this->option) ) {
            $optionKeys = $this->option;
            foreach ($optionKeys as $key => $value) {
                $result[] = array(
                    "name" => $this->getTranslate($value),
                    "price" => $value->price,
                    "stock" => $value->stock,
                    "thumbnailUrl" => $value->thumbnail_url,
                    "sku" => $value->sku,
                );
            }
        }
        return $result;
    }
    public function getSeller(){
        $seller = $this->seller;
        return [
            "name" => $this->getTranslate($seller),
            "rate" => $seller->rate,
        ];
    }
//    public function getBrandId($brandName){
//        return is_null($brandName['origin'])
//            ? null
//            : Brand::firstOrCreate(array(
//                "original" => $brandName['origin'],
//                "chinese" => $brandName['zh'],
//                "english" => $brandName['zh'],
//                "chinese" => $brandName['zh'],
//            ))->id;
//    }

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
    public function manufacturer()
    {
        return $this->hasOne('App\Models\Manufacturer','id','manufacturer_id');
    }

    // get reference data
    // hasMany('local_column_name','remote_table_column_name');
    public function option()
    {
        return $this->hasMany('App\Models\Option','product_id','id');
    }


    // get translate data
    public function translateName()
    {
        return $this->hasOne('App\Models\TranslateName','id','translate_name_id');
    }
    public function translateDescription()
    {
        return $this->hasOne('App\Models\TranslateDescription','id','translate_description_id');
    }

}
