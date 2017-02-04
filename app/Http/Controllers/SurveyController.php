<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Pager\PageController;
use App\Models\User;
use App\Models\Survey;
use App\Models\Interest;

use Log;
use Abort;

use App\Traits\InterestControllTraits;
use App\Traits\GetUserModelTrait;

class SurveyController extends Controller
{
    use InterestControllTraits,GetUserModelTrait;

    public function get(Request $request,$user_id){
        $user = User::findOrFail($user_id);
        $survey = $user->survey->first();

        $result = [
            "id" => $survey->id,
            "user" => [
                "email" => $user->email,
                "name" => $user->name,
                "gender" => $user->gender_id,
                "birthday" => $user->birthday,
                "location" => [
                    "city" => $user->city,
                    "address1" => $user->address1,
                    "address2" => $user->address2,
                    "postCode" => $user->post_code,
                ],
            ],
            "likeCategory" => [
                "categoryId" => $survey->interest['category_id'],
                "divisionId" => $survey->interest['division_id'],
            ],
            "survey" => [
                "purchasingFactor" => $survey->purchasing_factor,
                "majorStore" => $survey->major_store,
                "favoriteBrand" => $survey->favorite_brand,
                "connectionPath" => $survey->connection_path,
            ],
        ];

        return response()->success($result);
    }

    public function getList(Request $request){
        $query = $request->query();
        $controller = new PageController('survey',$query);
        $collection = $controller->getCollection();

        $result = (object)array(
            "totalCount" => $controller->totalCount,
            "currentPage" => $controller->currentPage,
        );
        foreach($collection as $survey){
            $user = $survey->user;
            $result->surveys[] = (object)array(
                "id" => $survey["id"],
                "user" => [
                    "email" => $user->email,
                    "name" => $user->name,
                    "gender" => $user->gender_id,
                    "birthday" => $user->birthday,
                    "location" => [
                        "city" => $user->city,
                        "address1" => $user->address1,
                        "address2" => $user->address2,
                        "postCode" => $user->post_code,
                    ],
                ],
                "likeCategory" => [
                    "categoryId" => $survey->interest['category_id'],
                    "divisionId" => $survey->interest['division_id'],
                ],
                "survey" => [
                    "purchasingFactor" => $survey->purchasing_factor,
                    "majorStore" => $survey->major_store,
                    "favoriteBrand" => $survey->favorite_brand,
                    "connectionPath" => $survey->connection_path,
                ],
            );
        };

        if(!empty($result->surveys)){
            return response()->success($result);
        }else{
            return response()->success();
        }
    }
    public function post(Request $request){
        $user = $this->getUserByTokenRequestOrFail($request);
        $survey = new Survey;

        if( !is_null($user->survey) ) Abort::Error('0046',"Already Written Survey User");

        $survey->user_id = $user->id;
        $user->email = $request['user']['email'];
        $user->name = $request['user']['name'];
        $user->gender_id = $request['user']['gender'];
        $user->birthday = $request['user']['birthday'];
        $user->city = $request['user']['location']['city'];
        $user->address1 = $request['user']['location']['address1'];
        $user->address2 = $request['user']['location']['address2'];
        $user->post_code = $request['user']['location']['postCode'];
        $survey->interest_id = Interest::create($this->setNewInterest($user,$request['likeCategory']))['id'];
        $survey->purchasing_factor = $request['survey']['purchasingFactor'];
        $survey->major_store = $request['survey']['majorStore'];
        $survey->favorite_brand = $request['survey']['favoriteBrand'];
        $survey->connection_path = $request['survey']['connectionPath'];

        if(!$user->save()) Abort::Error('0040','Check User Data');
        if($survey->save()){
            return response()->success($survey);
        }
    }
}
