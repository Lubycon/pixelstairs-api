<?php

namespace App\Classes;
use Log;
class Abort{

    public static function Error($errorCode,$devMsg = null){
        $data = config('error.'.$errorCode);
        $customJsonBefore = (object)array(
            "customCode" => $errorCode,
            "devMsg" => $devMsg,
        );
        $customJson = json_encode($customJsonBefore);
        Abort::excute($data->httpCode,$customJson);
    }
    private static function excute($httpCode,$json){
        throw new \App\Exceptions\CustomException($httpCode,$json);
    }
}
