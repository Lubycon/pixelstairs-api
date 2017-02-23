<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\TranslateName;
use App\Models\TranslateDescription;
use Log;
use Abort;
use Symfony\Component\HttpFoundation\HeaderBag;
use Request;

class BaseModel extends Model
{
    public $defaultLanguage = 'en';

    public function getTranslateResult($translate){
        return [
            'origin' => $translate['original']['original'],
            'zh' => $translate['chinese'],
            'ko' => $translate['korean'],
            'en' => $translate['english'],
        ];
    }
    public function getTranslateResultByLanguage($translate){
        $language = Request::header('x-mitty-language');
        if( is_null($language) ) Abort::Error("0047");
        if( is_array($translate) ){
            $result = [];
            foreach($translate as $key => $value){
                $value = $this->isTranslated($value);
                $data = $this->getTranslateResult($value);
                $result[] = isset($data[$language]) && $data[$language] != "" ? $data[$language] : $data[$this->defaultLanguage];
            }
            return $result;
        }else{
            $translate = $this->isTranslated($translate);
            $data = $this->getTranslateResult($translate);
            return isset($data[$language]) && $data[$language] != "" ? $data[$language] : $data[$this->defaultLanguage];
        }
    }
    public function isTranslated($value){
        if( is_null($value) ) return NULL;
        if( !isset($value['original']['original']) ){
            if( isset( $value['name_translate_id'] ) ) return $value->translateName;
            if( isset( $value['title_translate_id'] ) ) return $value->translateName;
            if( isset( $value['description_translate_id'] ) ) return $value->translateDescription;
        }
        return $value;
    }

    public function getImageObject($model){
        if( $model->image == null ){
            return null;
        }else{
            return $model->image->getObject();
        }
    }

    public function getImageGroupObject($model){
        if( $model->imageGroup == null ){
            return null;
        }else{
            return $model->imageGroup->getObjects();
        }
    }

}