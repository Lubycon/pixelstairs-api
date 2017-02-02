<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Pager\PageController;
use App\Models\User;
use App\Models\Survey;

use App\Traits\InterestControllTraits;

class SurveyController extends Controller
{
    use InterestControllTraits;

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
                    "name" => $user->name,
                    "email" => $user->email,
                    "phone" => $user->phone,
                ],
                "purchasingFactor" => $survey->purchasing_factor,
                "majorStore" => $survey->major_store,
                "favoriteBrand" => $survey->favorite_brand,
                "connectionPath" => $survey->connection_path,
                "likeCategory" => $this->getInterestAll($survey)
            );
        };

        if(!empty($result->surveys)){
            return response()->success($result);
        }else{
            return response()->success();
        }
    }
    public function post(Request $request,$user_id){
        $user = User::findOrFail($user_id);
        $survey = new Survey;

        $survey->user_id = $user->id;
        $user->email = $request['user']['email'];
        $user->name = $request['user']['name'];
        $user->gender_id = $request['user']['gender'];
        $user->birthday = $request['user']['birthday'];
        $user->city = $request['user']['location']['city'];
        $user->address1 = $request['user']['location']['address1'];
        $user->address2 = $request['user']['location']['address2'];
        $user->post_code = $request['user']['location']['postCode'];
        $interest = $user->interest()->saveMany($this->setNewInterest($request['likeCategory']));
        $this->setInterestId($survey,$interest);
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
