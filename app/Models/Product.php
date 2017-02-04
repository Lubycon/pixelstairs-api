<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Log;
use App\Models\section;

class Product extends BaseModel
{
    use SoftDeletes;

    protected $fillable = [
        'translate_name_id','translate_description_id','image_id','image_group_id'
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
        'weight' => 'int',
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
        $result = [];
        for( $i=0;$i<3;$i++ ){
            $id = $this->sectionGroup->sectionById($i)->first()['id'];
            if(is_null($id)) return $result;
            $result[] = $id;
        }
        return $result;
    }
    public function getSections(){
        $result = [];
        for( $i=0;$i<3;$i++ ){
            $section = $this->sectionGroup->sectionById($i)->first();
            if(is_null($section)) return $result;
            $result[] = $section;
        }
        return $result;
    }
    public function getOptionKey(){
        $result = [];
        if( count($this->option) ) {
            $optionKeys = $this->option->first()->optionCollection;
            for ($i = 0; $i < 4; $i++) {
                if (is_null($optionKeys['option_key_id_' . $i])) return $result;
                $result[] = $optionKeys->optionKey($i)->first();
            }
        }
        return $result;
    }
    public function getOptionCollection($options,$language){
        $optionDivide = $this->optionDivide($options);
        $result = [];
        if( count($this->option) ) {
            $optionKeys = $this->option->first()->optionCollection;
            for ($i = 0; $i < 4; $i++) {
                if (is_null($optionKeys['option_key_id_' . $i])) return $result;
                $result[] = [
                    "name" => $this->getTranslateResultByLanguage($optionKeys->optionKey($i)->first(),$language),
                    "values" => $optionDivide[$i],
//                    "thumbnailUrl" => null,
                ];
            }
        }
        return $result;
    }
    public function optionDivide($options){
        $result =[];
        foreach($options as $key=>$value){
            $explode = explode(',',$value['name']);
            foreach($explode as $int=>$name){
                if(!isset($result[$int])) $result[$int] = [];
                if( !in_array( $name , $result[$int] ) ){
                    $result[$int][] = $name;
                }
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
                    "safeStock" => $value->safe_stock,
                    "thumbnailUrl" => $value->image->getObject(),
                    "sku" => $value->sku,
                );
            }
        }
        return $result;
    }
//    public function getProvisionOption($language,$priceUnit){
//        $result = [];
//        if (count($this->option)) {
//            $optionKeys = $this->option;
//            foreach ($optionKeys as $key => $value) {
//                if( $value->stock > $value->safe_stock ){
//                    $result[] = array(
//                        "name" => $this->getTranslateResultByLanguage($value,$language),
//                        "price" => $value->price,
//                        "priceUnit" => $priceUnit,
//                        "stock" => $value->stock,
//                        "safeStock" => $value->safe_stock,
////                    "thumbnailUrl" => $value->thumbnail_url,
//                        "sku" => $value->sku,
//                    );
//                }
//            }
//        }
//        return $result;
//    }
    public function getSeller(){
        $seller = $this->seller;
        return [
            "name" => $seller->name,
            "rate" => $seller->rate,
        ];
    }
    public function getQuestions(){
        $result = [];
        $questions = $this->reviewQuestion;
        foreach($questions as $question){
            $questionKey = $question->questionKey;
            $result[] = [
                "id" => (int)$question->id,
                "qKeyId" => $questionKey->id,
                "qKey" => $questionKey->getTranslate($questionKey),
                "description" => $question->getTranslateDescription($question),
            ];
        }
        return $result;
    }
    public function getQuestionsByLanguage($language){
        $result = [];
        $questions = $this->reviewQuestion;
        foreach($questions as $question){
            $questionKey = $question->questionKey;
            $result[] = [
                "id" => (int)$question->id,
                "qKeyId" => $questionKey->id,
                "qKey" => $questionKey->getTranslateResultByLanguage($questionKey,$language),
                "description" => $question->getTranslateResultByLanguage($question,$language),
            ];
        }
        return $result;
    }

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
        return $this->hasOne('App\Models\Status','code','status_code');
    }
    public function manufacturer()
    {
        return $this->hasOne('App\Models\Manufacturer','id','manufacturer_country_id');
    }
    public function image()
    {
        return $this->hasOne('App\Models\Image','id','image_id');
    }
    public function imageGroup()
    {
        return $this->hasOne('App\Models\ImageGroup','id','image_group_id');
    }

    // get reference data
    // hasMany('remote_table_column_name','local_column_name');
    public function option()
    {
        return $this->hasMany('App\Models\Option','product_id','id');
    }
    public function reviewQuestion()
    {
        return $this->hasMany('App\Models\ReviewQuestion','product_id','id');
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
