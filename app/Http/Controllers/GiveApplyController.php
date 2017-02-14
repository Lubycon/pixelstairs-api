<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Pager\PageController;

use App\Models\GiveProduct;
use App\Models\Review;
use App\Models\User;

use App\Traits\GetUserModelTrait;

use App\Http\Requests\GiveApply\GiveApplyPostRequest;
use App\Http\Requests\GiveApply\GiveApplyGetRequest;

use Log;
use Abort;

class GiveApplyController extends Controller
{
    use GetUserModelTrait;

    protected $review;
    protected $user;
    protected $language;

    public function getList( GiveApplyGetRequest $request, $user_id){
        $this->language = $request->header('X-mitty-language');
        $query = $request->query();
        $query['search'] = "applyUserId:".$user_id;
        $controller = new PageController('give_product',$query);
        $collection = $controller->getCollection();

        $result = (object)array(
            "totalCount" => $controller->totalCount,
            "currentPage" => $controller->currentPage,
        );
        foreach($collection as $array){
            $result->reviews[] = (object)array(
                "id" => $array["id"],
                "title" => $array->review->title,
                "images" => $array->review->getImageGroupObject($array->review),
                "applyUserId" => $array['apply_user_id'],
                "user" => [
                    "id" => $array->review->user->id,
                    "name" => $array->review->user->name,
                    "profileImg" => $array->review->user->image->getObject()
                ],
                "product" => [
                    "id" => $array->review->product->id,
                    "haitaoProductId" => $array->review->product->id,
                    "title" => $array->review->product->getTranslateResultByLanguage($array->review->product->translateName,$this->language),
                    "categoryName" => $array->review->product->category->getTranslateResultByLanguage($array->review->product->category->translateName,$this->language),
                    "divisionName" => $array->review->product->division->getTranslateResultByLanguage($array->review->product->division->translateName,$this->language),
                    "sectionNames" => $array->review->product->getTranslateResultByLanguage($array->review->product->getSections(),$this->language),
                    "thumbnailUrl" => $array->review->product->image->getObject(),
                ],
                "giveStatusCode" => $array['give_status_code'],
            );
        };

        if(!empty($result->reviews)){
            return response()->success($result);
        }else{
            return response()->success();
        }
    }

    public function post( GiveApplyPostRequest $request , $review_id ){
        $this->user = $this->getUserByTokenRequestOrFail($request);
        $this->review = Review::findOrFail($review_id);
        $create = $this->review->applyProduct($this->user);
        return response()->success($create);
    }
}
