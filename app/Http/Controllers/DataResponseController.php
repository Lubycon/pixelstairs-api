<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Abort;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Product;
use App\Models\Country;
use App\Models\Sku;
use App\Models\Brand;
use App\Models\Option;
use App\Models\Category;
use App\Models\Division;
use App\Models\Status;

class DataResponseController extends Controller
{
    private function getModelByWhitelist($query){
        $whiteList = (object)array(
            'user' => User::all(),
            'product' => Product::all(),
            'country' => Country::all(),
            'sku' => Sku::all(),
            'brand' => Brand::all(),
            'option' => Option::all(),
            'category' => Category::all(),
            'division' => Division::all(),
            'status' => Status::all(),
        );
        $models = (object)array();
        foreach($query as $key => $value){
            if(isset($whiteList->$value)){
                $models->$key = $whiteList->$value;
            }else{
                return null;
            }
        };
        return $models;
    }

    public function dataSimpleResponse(Request $request){
        $query = $request->query();
        $models = $this->getModelByWhitelist($query);

        if( !is_null($models) ){
            return response()->success($models);
        }else{
            return response()->success();
        }
    }
}
