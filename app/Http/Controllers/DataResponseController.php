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
use App\Models\Market;
use App\Models\Sector;

class DataResponseController extends Controller
{
    /**
     * @SWG\Get(
     *     path="/data",
     *     summary="summary",
     *     description="descriptionssssss",
     *     operationId="opidddddd",
     *     produces={"application/json"},
     *     tags={"data"},
     *     @SWG\Parameter(
     *         name="country",
     *         in="query",
     *         description="param description",
     *         required=false,
     *         type="string",
     *         collectionFormat="multi"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *         @SWG\Schema(
     *             type="array",
     *             @SWG\Items(ref="#/definitions/Pet")
     *         ),
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Invalid status value",
     *     ),
     *     security={
     *       {"petstore_auth": {"write:pets", "read:pets"}}
     *     }
     * )
     */
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
            'sector' => Sector::all(),
            'status' => Status::all(),
            'market' => Market::all(),
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
