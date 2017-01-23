<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Abort;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Product;
use App\Models\Country;
use App\Models\Brand;
use App\Models\Option;
use App\Models\Category;
use App\Models\Division;
use App\Models\Status;
use App\Models\Market;
use App\Models\Section;
use App\Models\Unit;
use App\Models\Gender;

class DataResponseController extends Controller
{
//    /**
//     * @SWG\Get(
//     *     path="/data",
//     *     summary="Get Referer Data",
//     *     description="Relationship data refrer get",
//     *     operationId="data_get",
//     *     produces={"application/json"},
//     *     tags={"data"},
//     *     @SWG\Parameter(
//     *         name="country",
//     *         default="country",
//     *         in="query",
//     *         description="",
//     *         required=false,
//     *         type="string"
//     *     ),
//     *     @SWG\Parameter(
//     *         name="status",
//     *         in="query",
//     *         description="",
//     *         required=false,
//     *         type="string",
//     *     ),
//     *     @SWG\Parameter(
//     *         name="market",
//     *         in="query",
//     *         description="",
//     *         required=false,
//     *         type="string",
//     *     ),
//     *     @SWG\Parameter(
//     *         name="brand",
//     *         in="query",
//     *         description="",
//     *         required=false,
//     *         type="string",
//     *     ),
//     *     @SWG\Response(
//     *         response=200,
//     *         description="successful operation",
//     *     ),
//     *     @SWG\Response(
//     *         response="400",
//     *         description="Unexpected data value",
//     *     )
//     * )
//     */
    private function getModelByWhitelist($query){
        $whiteList = (object)array(
            'user' => User::all(),
            'product' => Product::all(),
            'country' => Country::all(),
            'brand' => Brand::all(),
            'option' => Option::all(),
            'category' => Category::all(),
            'division' => Division::all(),
            'section' => Section::all(),
            'status' => Status::all(),
            'market' => Market::all(),
            'unit' => Unit::all(),
            'gender' => Gender::all(),
        );
        $models = (object)array();
        foreach($query as $key => $value){
            if(isset($whiteList->$value)){
                $models->$key = $whiteList->$value;
            }else{
                $models->$key = NULL;
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
            Abort::Error('0040');
        }
    }
}
