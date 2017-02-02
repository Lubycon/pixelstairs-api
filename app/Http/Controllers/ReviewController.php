<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Pager\PageController;
use App\Models\Review;
use App\Models\Order;
use App\Models\Award;
use App\Models\Image;
use App\Models\ImageGroup;

use Log;
use Abort;

use App\Traits\GetUserModelTrait;
use App\Traits\ReviewAnswerControllTraits;
use App\Traits\OptionControllTraits;
use App\Traits\ReviewQuestionControllTraits;
use App\Traits\ImageControllTraits;
use App\Traits\S3StorageControllTraits;


class ReviewController extends Controller
{
    use GetUserModelTrait,ReviewAnswerControllTraits,OptionControllTraits,ReviewQuestionControllTraits,ImageControllTraits,S3StorageControllTraits;

    public $review;
    public $language;

    public function __construct(){
        $this->review = new review;
    }


    public function get(Request $request,$review_id){
        $this->language = $request->header('X-mitty-language');
        $this->review = Review::findOrFail($review_id);

        $user = $this->review->user;
        $product = $this->review->product;

        $response = [
            "id" => $this->review->id,
            "user" => [
                "id" => $user['id'],
                "name" => $user->name,
                "profileImg" => $user->image->getObject(),
            ],
            "product" => [
                "id" => $product->id,
                "haitaoProductId" => $product->haitao_product_id,
                "title" => $product->getTranslateResultByLanguage($product->translateName,$this->language),
                "description" => $product->getTranslateResultByLanguage($product->translateDescription,$this->language),
                "categoryName" => $product->category->getTranslateResultByLanguage($product->category->translateName,$this->language),
                "divisionName" => $product->division->getTranslateResultByLanguage($product->division->translateName,$this->language),
                "sectionNames" => $product->getTranslateResultByLanguage($product->getSections(),$this->language),
                "skuName" => $this->review->option->getTranslateResultByLanguage($this->review->option->translateName,$this->language),
            ],
            "title" => $this->review->title,
            "qa" => $this->getQnA($this->review->answer),
            "images" => $this->review->imageGroup->getImages(),
        ];

        return response()->success($response);
    }
    public function getList(Request $request){
        $this->language = $request->header('X-mitty-language');
        $query = $request->query();
        $controller = new PageController('review',$query);
        $collection = $controller->getCollection();

        $result = (object)array(
            "totalCount" => $controller->totalCount,
            "currentPage" => $controller->currentPage,
        );
        foreach($collection as $review){
            $this->review = Review::findOrFail($review['id']);
            $user = $this->review->user;
            $product = $this->review->product;

            $result->review[] = (object)array(
                "id" => $this->review->id,
                "user" => [
                    "id" => $user['id'],
                    "name" => $user->name,
                    "profileImg" => $user->image->getObject(),
                ],
                "product" => [
                    "id" => $product->id,
                    "haitaoProductId" => $product->haitao_product_id,
                    "title" => $product->getTranslateResultByLanguage($product->translateName,$this->language),
                    "description" => $product->getTranslateResultByLanguage($product->translateDescription,$this->language),
                    "categoryName" => $product->category->getTranslateResultByLanguage($product->category->translateName,$this->language),
                    "divisionName" => $product->division->getTranslateResultByLanguage($product->division->translateName,$this->language),
                    "sectionNames" => $product->getTranslateResultByLanguage($product->getSections(),$this->language),
                    "skuName" => $this->review->option->getTranslateResultByLanguage($this->review->option->translateName,$this->language),
                    "thumbnailUrl" => $product->image->getObject(),
                ],
                "title" => $this->review->title,
                "qa" => $this->getQnA($this->review->answer),
                "images" => $this->review->imageGroup->getImages(),
            );
        };

        if(!empty($result->review)){
            return response()->success($result);
        }else{
            return response()->success();
        }
    }
    public function getListByHaitaoProductId(Request $request,$haitao_product_id){
        $requestDuplicate = $request->duplicate(['search' => "haitaoProductId:$haitao_product_id"]);
        return $this->getList($requestDuplicate);
    }
    public function getListByHaitaoUserId(Request $request,$haitao_user_id){
        $requestDuplicate = $request->duplicate(['search' => "haitaoUserId:$haitao_user_id"]);
        return $this->getList($requestDuplicate);
    }
    public function post(Request $request,$target_id){
        $target = $this->getReviewTargetByRequest($request,$target_id);

        $this->review->user_id = $this->getUserByTokenRequestOrFail($request)['id'];
        $this->review->product_id = $target['product_id'];
        $this->review->title = $request->title;
        $this->review->sku = $target['sku'];
        $this->review->target = $request->target;
        $this->review->image_group_id = ImageGroup::create(['model_name'=>'review'])['id'];

        if ( !$this->review->save() ) Abort::Error("0040");
        if ( $this->review->answer()->saveMany($this->setNewReviewAnswer($request['answers']))  &&
             $this->review->imageGroup->image()->saveMany($this->createImageUploadArray($this->review,$request->images))
        ) return response()->success($this->review);
        Abort::Error("0040");
    }
    public function put(Request $request,$review_id){
        $this->review = Review::findOrFail($review_id);
        $this->review->title = $request->title;
        $this->updateAnswer($this->review,$request['answers']);

        if ( !$this->review->save() ) Abort::Error("0040");
        if($this->review->imageGroup->image()->saveMany($this->updateImageUploadArray($this->review,$request->images))
        )return response()->success($this->review);
    }
}
