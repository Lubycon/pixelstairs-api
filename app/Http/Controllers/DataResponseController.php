<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Abort;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Country;
use App\Models\Category;
use App\Models\Division;
use App\Models\Section;
use App\Models\Market;

use Log;

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
    public $language;

    private function getModelByWhitelist($query){
        $whiteList = (object)array(
            'country' => Country::all(),
            'market' => Market::all(),
            'category' => Category::all(),
            'division' => Division::all(),
            'section' => Section::all(),
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
        $this->language = $request->header('X-mitty-language');
        $query = $request->query();
        $models = $this->getModelByWhitelist($query);


        if( !is_null($models) ){
            foreach($models as $modelName => $modelInfo){
                if( count($modelInfo) == 0 ){}
                else if( isset($modelInfo[0]['translate_name_id']) ){
                    foreach($modelInfo as $key => $value ){
                        $value['name'] = $value->getTranslateResultByLanguage($value->translateName,$this->language);
                        unset(
                            $value['name_translate_id'],
                            $value->translateName
                        );
                    }
                }
            }
            return response()->success($models);
        }else{
            Abort::Error('0040');
        }
    }
}
