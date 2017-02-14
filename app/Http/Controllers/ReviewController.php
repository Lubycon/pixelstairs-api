<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Pager\PageController;
use App\Classes\FileUpload;

use App\Models\Review;
use App\Models\Order;
use App\Models\Award;
use App\Models\Image;
use App\Models\ImageGroup;

use Log;
use Abort;
use Carbon\Carbon;

use App\Traits\GetUserModelTrait;
use App\Traits\ReviewAnswerControllTraits;
use App\Traits\OptionControllTraits;
use App\Traits\ReviewQuestionControllTraits;
use App\Traits\ImageControllTraits;
use App\Traits\S3StorageControllTraits;

use App\Http\Requests\Review\ReviewPostRequest;
use App\Http\Requests\Review\ReviewPutRequest;


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
                "skuName" => $this->review->option->getTranslateResultByLanguage($this->review->option->translateName,$this->language),
                "thumbnailUrl" => $product->getImageObject($product),
            ],
            "title" => $this->review->title,
            "qa" => $this->getQnA($this->review->answer),
            "images" => $this->review->getImageGroupObject($this->review),
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
                    "skuName" => $this->review->option->getTranslateResultByLanguage($this->review->option->translateName,$this->language),
                    "thumbnailUrl" => $product->getImageObject($product),
                ],
                "title" => $this->review->title,
                "qa" => $this->getQnA($this->review->answer),
                "images" => $this->review->getImageGroupObject($this->review),
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
    public function post(ReviewPostRequest $request,$award_id){
        $award = Award::findOrFail($award_id);

        $this->review = new Review;
        $this->review->user_id = $this->getUserByTokenRequestOrFail($request)['id'];
        $this->review->product_id = $award->product_id;
        $this->review->option_id = $award->option_id;
        $this->review->award_id = $award_id;
        $this->review->title = $request->title;

        if ( $this->review->save() ){
            $this->review->answer()->saveMany($this->setNewReviewAnswer($request['answers']));
            $fileUpload = new FileUpload( $this->review, $request->images ,'image' );
            $this->review->image_group_id = $fileUpload->getResult();
            $this->review->save();
            return response()->success($this->review);
        }else{
            Abort::Error("0040");
        }
    }
    public function put(ReviewPutRequest $request,$review_id){
        $this->review = Review::findOrFail($review_id);
        $this->review->title = $request->title;
        $this->updateAnswer($this->review,$request['answers']);

        if ( $this->review->save() ){
            $fileUpload = new FileUpload( $this->review, $request->images ,'image' );
            $this->review->image_group_id = $fileUpload->getResult();
            $this->review->save();
            return response()->success($this->review);
        }else{
            Abort::Error("0040");
        }
    }



    public function expire(Request $request){
        $expireTarget = Review::where('expire_date','<',Carbon::now()->toDateTimeString())->get();
        foreach( $expireTarget as $review ){
            $freeGift = $review->product->freeGiftGroup->where;
            $return_stock = $review->give_stock;

            $review->give_stock = null;
            $review->save();
        }


        return response()->success($expireTarget);
    }
}
