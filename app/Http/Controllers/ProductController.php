<?php

namespace App\Http\Controllers;

use App\Models\SectionGroup;
use App\Models\TranslateName;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Pager\PageController;

use Carbon\Carbon;
use Log;

use App\Models\Status;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Seller;
use App\Models\Option;
use App\Models\Manufacturer;
use Abort;

use App\Traits\GetUserModelTrait;
use App\Traits\OptionControllTraits;
use App\Traits\HaitaoRequestTraits;
use App\Traits\StatusInfoTraits;
use App\Traits\TranslateTraits;
use App\Traits\SectionTrait;

class ProductController extends Controller
{
    use GetUserModelTrait,OptionControllTraits,HaitaoRequestTraits,StatusInfoTraits,TranslateTraits,SectionTrait;

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
            "haitaoProductId" => $product["haitao_product_id"],
            "marketId" => $product["market_id"],
            "title" => $product->getTranslate($product),
            "brand" => $product->getTranslate($product->brand),
            "description" => $product->getTranslateDescription($product),
            "categoryId" => $product["category_id"],
            "divisionId" => $product["division_id"],
            "section" => $product->getSectionIds(),
            "weight" => $product["weight"],
            "priceInfo" => $product->getPriceInfo(),
            "deliveryPrice" => $product["domestic_delivery_price"],
            "isFreeDelivery" => $product["is_free_delivery"],
            "thumbnailUrl" => $product["thumbnail_url"],
            "url" => $product["url"],
            "safeStock" => $product->option[0]->safe_stock,
            "statusCode" => $product["status_code"],
            "createDate" => Carbon::instance($product["created_at"])->toDateTimeString(),
            "startDate" => $product["start_date"],
            "endDate" => $product["end_date"],
            "optionKeys" => $product->getOptionKey(),
            "options" => $product->getOption(),
            "seller" => $product->getSeller(),
            "productGender" => $product->gender->id,
            "manufacturerCountryId" => $product->manufacturer['country_id'],
        );

        return response()->success($response);
    }

    public function getList(Request $request){
        $query = $request->query();
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
                "haitaoProductId" => $product["haitao_product_id"],
                "title" => $product->getTranslate($product),
                "brand" => $product->getTranslate($product->brand),
                "description" => $product->getTranslateDescription($product),
                "weight" => $product["weight"],
                "priceInfo" => $product->getPriceInfo(),
                "thumbnailUrl" => $product["thumbnail_url"],
                "url" => $product["url"],
                "safeStock" => $product->option[0]->safe_stock,
                "statusCode" => $product["status_code"],
                "endDate" => $product["end_date"],
            );
        };

        if(!empty($result->products)){
            return response()->success($result);
        }else{
            return response()->success();
        }
    }

    public function post(Request $request){
        $data = $request->json()->all();

        $this->market_product_id = $data["marketProductId"];
        $this->market_id = $data["marketId"];

        $this->product = new Product;
        $this->product->market_product_id = $this->market_product_id;
        $this->product->category_id = $data["categoryId"];
        $this->product->division_id = $data["divisionId"];
        $this->product->section_group_id = SectionGroup::firstOrCreate($this->setSectionGroup($data['section'],$this->product->division_id))['id'];
        $this->product->market_id = $data["marketId"];
        $this->product->brand_id = Brand::firstOrCreate($this->relationTranslateName($data['brandName']))['id'];
        $this->product->translate_name_id = $this->createTranslateName($data['title'])['id'];
        $this->product->translate_description_id = $this->createTranslateDescription($data['description'])['id'];
        $this->product->weight = $data["weight"];
        $this->product->original_price = $data["priceInfo"]['price'];
        $this->product->lower_price = $data["priceInfo"]['lowestPrice'];
        $this->product->unit = $data["priceInfo"]['unit'];
        $this->product->domestic_delivery_price = $data["deliveryPrice"];
        $this->product->is_free_delivery = $data["isFreeDelivery"];
        $this->product->thumbnail_url = $data["thumbnailUrl"];
        $this->product->url = $data["url"];
        $this->product->status_code = "0300";
        $this->product->end_date = Carbon::parse($data["endDate"])->timezone(config('app.timezone'))->toDateTimeString();
        $this->product->gender_id = $data['gender'];
        $this->product->manufacturer_country_id = $data['manufacturerCountryId'];
        $this->product->seller_id = Seller::firstOrCreate($data['seller'])['id'];
        $optionCollection = $this->createOptionCollection($data['optionKeys']['name']);

        if ( !$this->product->save() ) Abort::Error("0040");
        if ( $this->product->option()->saveMany($this->setNewOption($data['options']['option'],$data['safeStock'],$optionCollection)) ) return response()->success($this->product);
        Abort::Error("0040");
    }

    public function put(Request $request,$id){
        $data = $request->json()->all();

        $this->product = Product::findOrFail($id);
        $this->market_id = $this->product->market_id;
        $this->market_product_id = $this->product->market_product_id;

        $this->product->market_product_id = $this->market_product_id;
        $this->product->category_id = $data["categoryId"];
        $this->product->division_id = $data["divisionId"];
        $this->product->section_group_id = SectionGroup::firstOrCreate($this->setSectionGroup($data['section'],$this->product->division_id))['id'];
        $this->product->market_id = $data["marketId"];
        $this->product->brand_id = Brand::firstOrCreate($this->relationTranslateName($data['brandName']))['id'];
        $this->product->translate_name_id = $this->createTranslateName($data['title'])['id'];
        $this->product->translate_description_id = $this->createTranslateDescription($data['description'])['id'];
        $this->product->weight = $data["weight"];
        $this->product->original_price = $data["priceInfo"]['price'];
        $this->product->lower_price = $data["priceInfo"]['lowestPrice'];
        $this->product->unit = $data["priceInfo"]['unit'];
        $this->product->domestic_delivery_price = $data["deliveryPrice"];
        $this->product->is_free_delivery = $data["isFreeDelivery"];
        $this->product->thumbnail_url = $data["thumbnailUrl"];
        $this->product->url = $data["url"];
        $this->product->status_code = "0300";
        $this->product->end_date = Carbon::parse($data["endDate"])->timezone(config('app.timezone'))->toDateTimeString();
        $this->product->gender_id = $data['gender'];
        $this->product->manufacturer_country_id = $data['manufacturerCountryId'];
        $this->product->seller_id = Seller::firstOrCreate($data['seller'])['id'];
        $optionCollection = $this->createOptionCollection($data['optionKeys']['name']);

        if ( !$this->product->save() ) Abort::Error("0040");
        if ( $this->updateOptions($data['options']['option'],$data['safeStock'],$optionCollection) ) return response()->success($this->product);
        Abort::Error("0040");
    }

    public function delete(Request $request,$id){
        $this->product = Product::findOrFail($id);
        $this->product->option()->delete();
        if($this->product->delete()){
            return response()->success();
        }else {
            Abort::Error('0040');
        }
    }
    public function status(Request $request,$status_name){
        $status = Status::with('translateName')->get()->where('translateName.english',$status_name)->first();
        $products = $request['products'];
        foreach( $products as $value ){
            $this->product = Product::findOrFail($value);
            $this->product->status_code = $this->statusUpdate($request,$status['code']);
            $this->product->save();
        }
        return response()->success();
    }
}
