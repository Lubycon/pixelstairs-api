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
    public function getTranslateResultByLanguage($translate,$language){
        if(count($translate) > 1){
            $result = [];
            foreach($translate as $key => $value){
                $value = $this->isTranslateName($value);
                $data = $this->getTranslateResult($value);
                $result[] = $data[$language];
            }
            return $result;
        }else{
            $translate = $this->isTranslateName($translate);
            $data = $this->getTranslateResult($translate);
            return $data[$language];
        }
    }
    public function isTranslateName($value){
        if( !isset($value['original']['original']) ) return $value->translateName;
        return $value;
    }
}