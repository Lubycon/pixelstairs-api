<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TranslateName;
use App\Models\TranslateDescription;
use Log;
use Symfony\Component\HttpFoundation\HeaderBag;

class BaseModel extends Model
{
    public function getTranslate($translate){
        $result = [];

        if( is_array($translate) ){
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
        if( is_array($translate) ){
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
    public function getTranslateResultByLanguage($translate,$language){
        if($language == 'ko' || $language == 'en') $language = "origin";

        if( is_array($translate) ){
            $result = [];
            foreach($translate as $key => $value){
                $value = $this->isTranslated($value);
                $data = $this->getTranslateResult($value);
                $result[] = $data[$language];
            }
            return $result;
        }else{
            $translate = $this->isTranslated($translate);
            $data = $this->getTranslateResult($translate);
            return $data[$language];
        }
    }
    public function isTranslated($value){
        if( is_null($value) ) return NULL;
        if( !isset($value['original']['original']) ){
            if( isset( $value['translate_name_id'] ) ) return $value->translateName;
            if( isset( $value['translate_description_id'] ) ) return $value->translateDescription;
        }
        return $value;
    }
}