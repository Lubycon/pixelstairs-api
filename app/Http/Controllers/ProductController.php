<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Pager\PageController;

use Carbon\Carbon;
use Log;

use App\Models\Category;
use App\Models\Division;

use App\Models\Status;
use App\Models\Market;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Option;
use App\Models\Sku;
use Abort;
use App\Traits\GetUserModelTrait;

class ProductController extends Controller
{
    use GetUserModelTrait;

    public $product;
    public $product_id;
    public $market_id;
    public $market_category_id;

    public function haitaoData(Request $request){
        $product = Product::findOrFail(501);

        $response = (object)array(
            "mittyProductId" => $product["id"],
            "marketProductId" => $product["product_id"],
            "haitaoProductId" => "haitao present",
            "market" => (object)array(
                "id" => $product["market_id"],
                "name" => Market::wherecode($product["market_id"])->value("name"),
            ),
            "category" => array(
                "id" => $product["category_id"],
                "origin" => Category::find($product["category_id"])["original_name"],
                "zh" => Category::find($product["category_id"])["chinese_name"],
            ),
            "division" => array(
                "id" => $product["division_id"],
                "origin" => Division::find($product["division_id"])["original_name"],
                "zh" => Division::find($product["division_id"])["chinese_name"],
            ),
            "title" => (object)array(
                "origin" => $product["original_title"],
                "zh" => $product["chinese_title"],
            ),
            "brand" => array(
                "id" => $product["brand_id"],
                "name" => Brand::find($product["brand_id"])["name"],
            ),
            "description" => $product["description"],
            "price" => $product["price"],
            "deliveryPrice" => $product["domestic_delivery_price"],
            "isFreeDelivery" => $product["is_free_delivery"],
            "stock" => $product["stock"],
            "safeStock" => $product["safe_stock"],
            "url" => $product["url"],
            "status" => array(
                "code" => $product["status_code"],
                "name" => Status::wherecode($product["status_code"])->value("name"),
            ),
            "createDate" => Carbon::instance($product["created_at"])->toDateTimeString(),
            "startDate" => $product["start_date"],
            "endDate" => $product["end_date"],
            "options" => $this->bindOption(Option::whereproduct_id($product["id"])->get())
        );

        return response()->success($response);
    }

    public function get(Request $request,$id){
        $product = Product::findOrFail($id);
        $response = (object)array(
            "id" => $product["id"],
            "marketProductId" => $product["product_id"],
            "haitaoId" => $product["haitao_product_id"],
            "marketId" => $product["market_id"],
            "categoryId" => $product["category_id"],
            "divisionId" => $product["division_id"],
            "marketCategoryId" => Division::find($product["division_id"])["market_category_id"],
            "title" => (object)array(
                "origin" => $product["original_title"],
                // "ko" => $product["korean_title"],
                // "en" => $product["english_title"],
                "zh" => $product["chinese_title"],
            ),
            "brandName" => Brand::find($product["brand_id"])->value("name"),
            "description" => $product["description"],
            "price" => $product["price"],
            "deliveryPrice" => $product["domestic_delivery_price"],
            "isFreeDelivery" => $product["is_free_delivery"],
            "stock" => $product["stock"],
            "safeStock" => $product["safe_stock"],
            "url" => $product["url"],
            "statusCode" => $product["status_code"],
            "createDate" => Carbon::instance($product["created_at"])->toDateTimeString(),
            "startDate" => $product["start_date"],
            "endDate" => $product["end_date"],
            "options" => $this->bindOption(Option::whereproduct_id($product["id"])->get())
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
        foreach($collection as $array){
            $result->products[] = (object)array(
                "id" => $array["id"],
                "marketProductId" => $array["product_id"],
                "haitaoId" => $array["haitao_product_id"],
                "title" => (object)array(
                    "origin" => $array["original_title"],
                    // "ko" => $array["korean_title"],
                    // "en" => $array["english_title"],
                    "zh" => $array["chinese_title"],
                ),
                "brandName" => is_null($array["brand_id"]) ? NULL : Brand::findOrFail($array["brand_id"])->value("name"),

                "description" => $array["description"],
                "price" => $array["price"],
                "stock" => $array["stock"],
                "safeStock" => $array["safe_stock"],
                "url" => $array["url"],
                "status_code" => $array["status_code"],
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

        $this->product_id = $data["marketProductId"];
        $this->market_id = $data["marketId"];
//        $this->market_category_id = $data["marketDivisionId"];

        $this->product = new Product;
        $this->product->product_id = $this->product_id;
        $this->product->category_id = $this->getCategoryId($data["categoryId"]);
        $this->product->division_id = $this->getDivisionId($data["divisionId"]);
        $this->product->market_id = $data["marketId"];
        $this->product->brand_id = $this->getBrandId($data["brandName"]);
        $this->product->original_title = $data["title"]["origin"];
        // $product->korean_title = $data["title"]["ko"];
        // $product->english_title = $data["title"]["en"];
        $this->product->chinese_title = $data["title"]["zh"];
        $this->product->description = $data["description"];
        $this->product->price = $data["price"];
        $this->product->domestic_delivery_price = $data["deliveryPrice"];
        $this->product->is_free_delivery = $data["isFreeDelivery"];
        $this->product->stock = $data["stock"];
        $this->product->safe_stock = $data["safeStock"];
        $this->product->url = $data["url"];
        $this->product->status_code = "0300";
        $this->product->end_date = $data["endDate"];

        if ( !$this->product->save() ) Abort::Error("0040");

        if ( Option::insert($this->setOption($data["options"])) ) return response()->success($this->product);
        Abort::Error("0040");
    }

    public function put(Request $request,$id){
        $data = $request->json()->all();

        $this->product = Product::findOrFail($id);
        $options = $data["options"];

        $this->market_id = $this->product->market_id;
//        $this->market_category_id = $data["marketDivisionId"];

        $this->product->product_id = $data["marketProductId"];
        $this->product->category_id = $this->getCategoryId($data["categoryId"]);
        $this->product->division_id = $this->getDivisionId($data["divisionId"]);
        $this->product->market_id = $data["marketId"];
        $this->product->brand_id = $this->getBrandId($data["brandName"]);
        $this->product->original_title = $data["title"]["origin"];
        // $product->korean_title = $data["title"]["ko"];
        // $product->english_title = $data["title"]["en"];
        $this->product->chinese_title = $data["title"]["zh"];
        $this->product->description = $data["description"];
        $this->product->price = $data["price"];
        $this->product->domestic_delivery_price = $data["deliveryPrice"];
        $this->product->is_free_delivery = $data["isFreeDelivery"];
        $this->product->stock = $data["stock"];
        $this->product->safe_stock = $data["safeStock"];
        $this->product->url = $data["url"];
        $this->product->status_code = $this->statusUpdate($request);
        $this->product->start_date = Carbon::now()->toDateTimeString();
        $this->product->end_date = $data["endDate"];


        if ( !$this->product->save() ) Abort::Error("0040");
        if ($this->updateOptions($options)) return response()->success($this->product);
        Abort::Error("0040");
    }

    private function getBrandId($brand_name){
        return is_null($brand_name) ? null : Brand::firstOrCreate(["name" => $brand_name])->id;
    }
    private function getCategoryId($category){
        return is_array($category)
        ? Category::firstOrCreate(
            array(
                "original_name" => $category["origin"],
                "chinese_name" => $category["zh"],
            ))["id"]
        : Category::findOrFail($category)->value("id");
    }
    private function getDivisionId($division){
        return is_array($division)
        ? Division::firstOrCreate(
            array(
                "parent_id" => $this->product->category_id,
                "market_id" => $this->market_id,
                "market_category_id" => $this->market_category_id,
                "original_name" => $division["origin"],
                "chinese_name" => $division["zh"],
            ))["id"]
        : Division::findOrFail($division)->value("id");
    }

    private function bindOption($option){
        $response = [];
        foreach ($option as $key => $value) {
            $response[] = array(
                "skuId" => Sku::find($value->sku_id)->value("sku"),
                "name" => array(
                    "origin" => $value->original_name,
                    "zh" => $value->chinese_name,
                ),
                "price" => $value->price
            );
        }
        return $response;
    }

    private function setOption($options){
        $result = [];
        $index = 0;
        foreach ($options as $key => $option) {
            $result[] = array(
                "market_id" => $this->market_id,
                "product_id" => $this->product->id,
                "sku_id" => $this->createSku($option,$index),
                "original_name" => $option["name"]["origin"],
                "chinese_name" => $option["name"]["zh"],
                // "korean_name" => $option["name"]["ko"],
                // "english_name" => $option["name"]["en"],
                "price" => $option["price"],
                // "stock" => $option["stock"],
                // "safe_stock" => $option["safeStock"],
            );
            $index++;
        }
        return $result;
    }

    private function updateOptions($options){
        $this->isDirdyOption($options);
        $checkedArray = [];
        foreach ($options as $key => $value) {
            $targetOption = Option::wheresku_id($value["sku"])->firstOrFail();
            $targetOption["original_name"] = $value["name"]["origin"];
            $targetOption["chinese_name"] = $value["name"]["zh"];
            $targetOption["price"] = $value["price"];
            if (!$targetOption->save()) Abort::Error("0040","Option Update Fail");

            $targetSku = Sku::whereid($targetOption["sku_id"])->whereproduct_id($this->product->id)->firstOrFail();
            $targetSku["description"] = $value["name"]["origin"];
            if (!$targetSku->save()) Abort::Error("0040","Sku Update Fail");
        }
        return true;
    }

    private function isDirdyOption($options){
        Log::info(count($this->product->option()->get()));
        Log::info(count($options));
        if ( count($this->product->option()->get()) !==  count($options)) Abort::Error("0040","Can not add option at update product");
        return false;
    }

    private function createSku($option,$index){
        $sku = array(
            "market_id" => $this->market_id,
            "product_id" => $this->product->id,
            "sku" => "MK".$this->market_id."PD".$this->product_id."ID".$index,
            "description" => $option["name"]["origin"],
        );
        $id = Sku::firstOrCreate($sku)->id;
        return $id;
    }
    private function statusUpdate($request){
        $user = $this->getUserByTokenRequestOrFail($request);
        if ( $this->product->status_code != $request->statusCode ){
            if ( $user->grade != "superAdmin" ) Abort::Error("0043","Can not change status");
            $this->product->start_date = Carbon::now()->toDateTimeString();
        }
        return $request->statusCode;
    }
}
