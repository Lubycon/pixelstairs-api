<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TranslateName;
use App\Models\TranslateDescription;
use Log;

class BaseModel extends Model
{
    // get translate data
    public function translateName()
    {
        return $this->hasOne('App\Models\TranslateName','id','translate_name_id');
    }
    public function translateDescription()
    {
        return $this->hasOne('App\Models\TranslateDescription', 'id', 'translate_description_id');
    }

    public function getTranslate($translate){
        $result = [];
        if( count($translate) > 1 ){
            foreach( $translate as $key => $array ){
                $result[] = $this->getTranslateResult($array->translateName);
            }
        }else{
            $result = $this->getTranslateResult($translate->translateName);
        }
        return $result;
    }
    public function getTranslateDescription($translate){
        $result = [];
        if( count($translate) > 1 ){
            foreach( $translate as $key => $array ){
                $result[] = $this->getTranslateResult($array->translateDescription);
            }
        }else{
            $result = $this->getTranslateResult($translate->translateDescription);
        }
        return $result;
    }
    public function getTranslateResult($translate){
        return [
            'origin' => $translate['original']['original'],
            'zh' => $translate['chinese'],
            'ko' => $translate['korean'],
            'en' => $translate['english'],
        ];
    }
}