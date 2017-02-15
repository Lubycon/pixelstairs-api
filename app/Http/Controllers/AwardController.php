<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Pager\PageController;
use Abort;
use Log;
use App\Http\Requests\Award\AwardGetUserRequest;

class AwardController extends Controller
{
    public $award;
    public $language;

    public function getList(Request $request){
        $this->language = $request->header('X-mitty-language');
        $query = $request->query();
        $controller = new PageController('award',$query);
        $collection = $controller->getCollection();

        $result = (object)array(
            "totalCount" => $controller->totalCount,
            "currentPage" => $controller->currentPage,
        );

        foreach($collection as $award){
            $this->award = $award;
            $user = $this->award->user;
            $product = $this->award->product;

            $result->award[] = (object)array(
                "type" => "award",
                "id" => $this->award->id,
                "expireDate" => $this->award->expire_date,
                "target" => $this->award->target,
                "user" => [
                    "id" => $user->id,
                    "name" => $user->name,
                    "profileImg" => $user->getImageObject($user),
                ],
                "product" => [
                    "id" => $product->id,
                    "haitaoProductId" => $product->haitao_product_id,
                    "title" => $product->getTranslateResultByLanguage($product->translateName,$this->language),
                    "description" => $product->getTranslateResultByLanguage($product->translateDescription,$this->language),
                    "categoryName" => $product->category->getTranslateResultByLanguage($product->category->translateName,$this->language),
                    "divisionName" => $product->division->getTranslateResultByLanguage($product->division->translateName,$this->language),
                    "sectionNames" => $product->getTranslateResultByLanguage($product->getSections(),$this->language),
                    "skuName" => $this->award->option->getTranslateResultByLanguage($this->award->option->translateName,$this->language),
                    "thumbnailUrl" => $product->getImageObject($product),
                ],
                "createdTime" => $this->award->created_at->format('Y-m-d H:i:s'),
            );
        };

        if(!empty($result->award)){
            return response()->success($result);
        }else{
            return response()->success();
        }
    }

    public function getListByUserId(AwardGetUserRequest $request,$user_id){
        $this->language = $request->header('X-mitty-language');
        $query = $request->query();
        $query['filter'] = "userId:".$user_id."||isWrittenReview:false";
        $controller = new PageController('award',$query);
        $collection = $controller->getCollection();

        $result = (object)array(
            "totalCount" => $controller->totalCount,
            "currentPage" => $controller->currentPage,
        );

        foreach($collection as $award) {
            $this->award = $award;
            $user = $this->award->user;
            $product = $this->award->product;

            $result->award[] = (object)array(
                "type" => "award",
                "id" => $this->award->id,
                "expireDate" => $this->award->expire_date,
                "target" => $this->award->target,
                "user" => [
                    "id" => $user->id,
                    "name" => $user->name,
                    "profileImg" => $user->getImageObject($user),
                ],
                "product" => [
                    "id" => $product->id,
                    "haitaoProductId" => $product->haitao_product_id,
                    "title" => $product->getTranslateResultByLanguage($product->translateName, $this->language),
                    "description" => $product->getTranslateResultByLanguage($product->translateDescription, $this->language),
                    "categoryName" => $product->category->getTranslateResultByLanguage($product->category->translateName, $this->language),
                    "divisionName" => $product->division->getTranslateResultByLanguage($product->division->translateName, $this->language),
                    "sectionNames" => $product->getTranslateResultByLanguage($product->getSections(), $this->language),
                    "skuName" => $this->award->option->getTranslateResultByLanguage($this->award->option->translateName, $this->language),
                    "thumbnailUrl" => $product->getImageObject($product),
                ],
                "createdTime" => $this->award->created_at->format('Y-m-d H:i:s'),
            );
        }

        if(!empty($result->award)){
            return response()->success($result);
        }else{
            return response()->success();
        }
    }
}
