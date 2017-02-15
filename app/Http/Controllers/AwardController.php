<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Pager\PageController;
use Abort;
use Log;

class AwardController extends Controller
{
    public $award;

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
            );
        };

        if(!empty($result->award)){
            return response()->success($result);
        }else{
            return response()->success();
        }
    }
}
