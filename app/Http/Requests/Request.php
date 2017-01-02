<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Abort;

abstract class Request extends FormRequest
{
    public function forbiddenResponse()
    {
        Abort::Error('0043');
    }
    public function response(array $errors)
    {
        Abort::Error('0052',$errors);
    }
    
    public function ruleMapping($required,$validate){
        $longArray = count($required) > count($validate) ? $required : $validate;
        $shortArray = count($required) < count($validate) ? $required : $validate;
        $newArray;
        foreach($longArray as $key => $value){
            if( array_key_exists($key,$shortArray) ){
                $newArray[$key] = $longArray[$key].'|'.$shortArray[$key];
            }else{
                $newArray[$key] = $longArray[$key];
            }
        }
        return $newArray;
    }
}
