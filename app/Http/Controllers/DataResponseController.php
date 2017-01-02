<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Abort;
use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\User;
use App\Models\Occupation;
use App\Models\Country;

use App\Models\Post;
use App\Models\PostSort;

use App\Models\Content;
use App\Models\ContentSort;
use App\Models\ContentCategory;

class DataResponseController extends Controller
{
    private function getModelByWhitelist($query){
        $whiteList = (object)array(
            'user' => User::all(), //only test data
            'job' => Occupation::all(),
            'country' => Country::all(),

            'post' => Post::all(), //only test data
            'postSort' => PostSort::all(),

            // 'content' => Content::all(), //not builded yet
            'contentSort' => ContentSort::all(),
            'contentCategory' => ContentCategory::all(),
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
