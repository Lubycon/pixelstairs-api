<?php

namespace App\Http\Controllers\Data;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use Log;

class DataResponseController extends Controller
{
//    private function getModelByWhitelist($query){
//        $whiteList = (object)array(
//        );
//        $models = (object)array();
//        foreach($query as $key => $value){
//            if(isset($whiteList->$value)){
//                $models->$key = $whiteList->$value;
//            }else{
//                $models->$key = NULL;
//            }
//        };
//        return $models;
//    }
//
//    public function dataSimpleResponse(Request $request){
//        $query = $request->query();
//        $models = $this->getModelByWhitelist($query);
//
//        if( !is_null($models) ){
//            return response()->success($models);
//        }else{
//            Abort::Error('0040');
//        }
//    }
}
