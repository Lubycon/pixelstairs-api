<?php

namespace App\Http\Controllers;

use App\Models\ImageGroup;
use App\Models\SectionGroup;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Pager\PageController;
use App\Classes\FileUpload;

use Carbon\Carbon;
use Log;

use App\Models\Product;
use Abort;

use App\Traits\GetUserModelTrait;
use App\Traits\OptionControllTraits;
use App\Traits\StatusInfoTraits;
use App\Traits\SectionTrait;

class ProductController extends Controller
{
    use GetUserModelTrait,
        OptionControllTraits,
        StatusInfoTraits,
        SectionTrait;

    public $product;
    public $product_id;
    public $market_id;
    public $market_product_id;
    public $market_category_id;

    public function get(Request $request,$id){
        $product = Product::findOrFail($id);
        $response = (object)array(
            "id" => $product["id"],
            "marketProductId" => $product["market_product_id"],
            "marketId" => $product->market->code,
            "title" => $product->getTranslateResultByLanguage($product->translateName),
            "brand" => $product->brand->getTranslateResultByLanguage($product->brand->translateName),
            "description" => $product->getTranslateResultByLanguage($product->translateDescription),
            "categoryName" => $product->category->getTranslateResultByLanguage($product->category->translateName),
            "divisionName" => $product->division->getTranslateResultByLanguage($product->division->translateName),
            "sections" => $product->getTranslateResultByLanguage($product->getSections()),
            "weight" => $product["weight"],
            "priceInfo" => $product->getPriceInfo(),
            "deliveryPrice" => $product["domestic_delivery_price"],
            "isFreeDelivery" => $product["is_free_delivery"],
            "thumbnailUrl" => $product->getImageObject($product),
            "images" => $product->getImageGroupObject($product),
            "url" => $product["url"],
            "safeStock" => $product->option[0]->safe_stock,
            "isLimited" => $product['isLimited'],
            "productStatusCode" => $product["product_status_code"],
            "createDate" => Carbon::instance($product["created_at"])->toDateTimeString(),
            "startDate" => $product["start_date"],
            "endDate" => $product["end_date"],
            "optionKeys" => $product->getTranslateResultByLanguage($product->getOptionKey()),
            "options" => $product->getOptionTranslate(),
            "seller" => $product->getSeller(),
            "productGender" => $product->gender->id,
            "manufacturerCountryId" => $product->manufacturer_country_id,
//            "questions" => $product->getQuestions(),
        );

        return response()->success($response);
    }

    public function getSimple(Request $request,$id){
        $product = Product::findOrFail($id);
        $response = (object)array(
            "id" => $product["id"],
            "title" => $product->getTranslateResultByLanguage($product->translateName),
            "brand" => $product->brand->getTranslateResultByLanguage($product->brand->translateName),
            "description" => $product->getTranslateResultByLanguage($product->translateDescription),
            "categoryName" => $product->category->getTranslateResultByLanguage($product->category->translateName),
            "divisionName" => $product->division->getTranslateResultByLanguage($product->division->translateName),
            "sections" => $product->getTranslateResultByLanguage($product->getSections()),
            "thumbnailUrl" => $product->getImageObject($product),
//            "questions" => $product->getQuestionsByLanguage($this->language),
        );

        return response()->success($response);
    }

    public function getList(Request $request){
        $this->language = $request->header('X-mitty-language');
        $query = $request->query();
        $baseQuery = "productStatusCode:0301";
        $query['search'] = isset($query['search'])
            ? $query['search'].$baseQuery
            : $baseQuery;

        $controller = new PageController('product',$query);
        $collection = $controller->getCollection();

        $result = (object)array(
            "totalCount" => $controller->totalCount,
            "currentPage" => $controller->currentPage,
        );
        foreach($collection as $product){
            $result->products[] = (object)array(
                "id" => $product["id"],
                "marketProductId" => $product["market_product_id"],
                "title" => $product->getTranslateResultByLanguage($product->translateName),
                "brand" => $product->brand->getTranslateResultByLanguage($product->brand->translateName),
                "description" => $product->getTranslateResultByLanguage($product->translateDescription),
                "weight" => $product["weight"],
                "priceInfo" => $product->getPriceInfo(),
                "thumbnailUrl" => $product->getImageObject($product),
                "url" => $product["url"],
                "safeStock" => $product->option[0]->safe_stock,
                "productStatusCode" => $product["product_status_code"],
                "endDate" => $product["end_date"],
            );
        };

        if(!empty($result->products)){
            return response()->success($result);
        }else{
            return response()->success();
        }
    }
//
//    public function post(ProductPostRequest $request){
//        $data = $request->json()->all();
//
//        $this->market_product_id = $data["marketProductId"];
//        $this->market_id = $data["marketId"];
//
//        $this->product = new Product;
//        $this->product->market_product_id = $this->market_product_id;
//        $this->product->category_id = $data["categoryId"];
//        $this->product->division_id = $data["divisionId"];
//        $this->product->section_group_id = SectionGroup::firstOrCreate($this->setSectionGroup($data['sections'],$this->product->division_id))['id'];
//        $this->product->market_id = Market::wherecode($data["marketId"])->first()['id'];
//        $this->product->brand_id = Brand::firstOrCreate($this->relationTranslateName($data['brand']))['id'];
//        $this->product->translate_name_id = $this->createTranslateName($data['title'])['id'];
//        $this->product->translate_description_id = $this->createTranslateDescription($data['description'])['id'];
//        $this->product->weight = $data["weight"];
//        $this->product->original_price = $data["priceInfo"]['price'];
//        $this->product->lower_price = $data["priceInfo"]['lowestPrice'];
//        $this->product->unit = $data["priceInfo"]['unit'];
//        $this->product->domestic_delivery_price = $data["deliveryPrice"];
//        $this->product->is_free_delivery = $data["isFreeDelivery"];
//
//
//        $this->product->url = $data["url"];
//        $this->product->product_status_code = "0300";
//        $this->product->is_limited = $data['isLimited'];
//        $this->product->end_date = Carbon::parse($data["endDate"])->timezone(config('app.timezone'))->toDateTimeString();
//        $this->product->gender_id = $data['productGender'];
//        $this->product->manufacturer_country_id = $data['manufacturerCountryId'];
//        $this->product->seller_id = Seller::firstOrCreate($data['seller'])['id'];
//        $optionCollection = $this->createOptionCollection($data['optionKeys']);
//
//        if( is_null( $data['questions'] ) ){$reviewQuestions = null;
//        }else{$reviewQuestions= $this->createReviewQuestions($data['questions']);}
//
//        if ( $this->product->save() ){
//            $this->product->option()->saveMany($this->setNewOption($data['options'],$data['safeStock'],$optionCollection));
//            $fileUpload = new FileUpload( $this->product,$data["thumbnailUrl"] ,'image' );
//            $this->product->image_id = $fileUpload->getResult();
//            $fileUpload = new FileUpload( $this->product,$data['detailImages'] ,'image' );
//            $this->product->image_group_id = $fileUpload->getResult();
//            $this->product->save();
//            if( !is_null($reviewQuestions) ) $this->product->reviewQuestion()->saveMany( $reviewQuestions );
//            return response()->success($this->product);
//        }else{
//            Abort::Error("0040");
//        }
//    }
//
//    public function put(ProductPutRequest $request,$id){
//        // product put method dose not have any image update logic
//
//        $data = $request->json()->all();
//
//        $this->product = Product::findOrFail($id);
//        $this->market_id = $this->product->market_id;
//        $this->market_product_id = $this->product->market_product_id;
//
//        $this->product->market_product_id = $this->market_product_id;
//        $this->product->category_id = $data["categoryId"];
//        $this->product->division_id = $data["divisionId"];
//        $this->product->section_group_id = SectionGroup::firstOrCreate($this->setSectionGroup($data['sections'],$this->product->division_id))['id'];
//        $this->product->market_id = Market::wherecode($data["marketId"])->first()['id'];
//        $this->product->brand_id = Brand::firstOrCreate($this->relationTranslateName($data['brand']))['id'];
//        $this->product->translate_name_id = $this->createTranslateName($data['title'])['id'];
//        $this->product->translate_description_id = $this->createTranslateDescription($data['description'])['id'];
//        $this->product->weight = $data["weight"];
//        $this->product->original_price = $data["priceInfo"]['price'];
//        $this->product->lower_price = $data["priceInfo"]['lowestPrice'];
//        $this->product->unit = $data["priceInfo"]['unit'];
//        $this->product->domestic_delivery_price = $data["deliveryPrice"];
//        $this->product->is_free_delivery = $data["isFreeDelivery"];
//        $this->product->url = $data["url"];
//        $this->product->is_limited = $data['isLimited'];
//        $this->product->end_date = Carbon::parse($data["endDate"])->timezone(config('app.timezone'))->toDateTimeString();
//        $this->product->gender_id = $data['productGender'];
//        $this->product->manufacturer_country_id = $data['manufacturerCountryId'];
//        $this->product->seller_id = Seller::firstOrCreate($data['seller'])['id'];
//        $optionCollection = $this->createOptionCollection($data['optionKeys']);
//        $this->updateReviewQuestions($this->product,$data['questions']);
//
//        if ( $this->product->save() ){
//            $this->updateOptions($this->product,$data['options'],$data['safeStock'],$optionCollection);
////            $fileUpload = new FileUpload( $this->product,$data["thumbnailUrl"] ,'image' );
////            $this->product->image_id = $fileUpload->getResult();
////            $fileUpload = new FileUpload( $this->product,$data['detailImages'] ,'image' );
////            $this->product->image_group_id = $fileUpload->getResult();
////            $this->product->save();
//            return response()->success($this->product);
//        }else{
//            Abort::Error("0040");
//        }
//    }
//
//    public function delete(ProductDeleteRequest $request,$id){
//        $this->product = Product::findOrFail($id);
//        $this->product->option()->delete();
//        if($this->product->delete()){
//            return response()->success();
//        }else {
//            Abort::Error('0040');
//        }
//    }
//    public function status(ProductStatusRequest $request,$status_name){
//        $status = Status::with('translateName')->get()->where('translateName.english',$status_name)->first();
//        $products = $request['products'];
//        $result = [];
//        foreach( $products as $value ){
//            $result[] = $product = $this->statusUpdate($request,Product::findOrFail($value),$status['code']);
//            $product->save();
//        }
//        return response()->success($result);
//    }
}
