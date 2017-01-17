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
use App\Models\Sector;

use App\Models\Status;
use App\Models\Market;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Option;
use App\Models\Sku;
use Abort;
use App\Traits\GetUserModelTrait;
use App\Traits\OptionControllTraits;

class ProductController extends Controller
{
    use GetUserModelTrait,OptionControllTraits;

    public $product;
    public $product_id;
    public $market_id;
    public $market_category_id;

    public function get(Request $request,$id){
        $product = Product::findOrFail($id);
        $response = (object)array(
            "id" => $product["id"],
            "marketProductId" => $product["product_id"],
            "haitaoId" => $product["haitao_product_id"],
            "marketId" => $product["market_id"],
            "categoryId" => $product["category_id"],
            "divisionId" => $product["division_id"],
            "sector" => $product->sectors(),
            "title" => (object)array(
                "origin" => $product["original_title"],
                "zh" => $product["chinese_title"],
            ),
            "brand" => array(
                'origin' => is_null($product["brand_id"]) ? NULL : Brand::find($product["brand_id"])->value("original_name"),
                'zh' => is_null($product["brand_id"]) ? NULL : Brand::find($product["brand_id"])->value("chinese_name"),
            ),
            "description" =>array(
                'origin' => $product["original_description"],
                'zh' => $product["chinese_description"],
            ),
            "weight" => $product["weight"],
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
                "brand" => array(
                    'origin' => is_null($array["brand_id"]) ? NULL : Brand::find($array["brand_id"])["original_name"],
                    'zh' => is_null($array["brand_id"]) ? NULL : Brand::find($array["brand_id"])["chinese_name"],
                ),
                "description" =>array(
                    'origin' => $array["original_description"],
                    'zh' => $array["chinese_description"],
                ),
                "weight" => $array["weight"],
                "price" => $array["price"],
                "stock" => $array["stock"],
                "safeStock" => $array["safe_stock"],
                "url" => $array["url"],
                "statusCode" => $array["status_code"],
                "endDate" => $array["end_date"],
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
        $this->product->category_id = $data["categoryId"];
        $this->product->division_id = $data["divisionId"];
        $this->product->sector_id_0 = $data["sector"][0];
        $this->product->sector_id_1 = isset($data["sector"][1]) ? $data["sector"][1] : NULL;
        $this->product->sector_id_2 = isset($data["sector"][2]) ? $data["sector"][2] : NULL;
        $this->product->market_id = $data["marketId"];
        $this->product->brand_id = $this->getBrandId($data["brand"]);
        $this->product->original_title = $data["title"]["origin"];
        $this->product->chinese_title = $data["title"]["zh"];
        $this->product->original_description = $data["description"]['origin'];
        $this->product->chinese_description = $data["description"]['zh'];
        $this->product->weight = $data["weight"];
        $this->product->price = $data["price"];
        $this->product->domestic_delivery_price = $data["deliveryPrice"];
        $this->product->is_free_delivery = $data["isFreeDelivery"];
        $this->product->stock = $data["stock"];
        $this->product->safe_stock = $data["safeStock"];
        $this->product->url = $data["url"];
        $this->product->status_code = "0300";
        $this->product->end_date = Carbon::parse($data["endDate"])->timezone(config('app.timezone'))->toDateTimeString();
        if ( !$this->product->save() ) Abort::Error("0040");

        if ( Option::insert($this->setOption($data["options"])) ) return response()->success($this->product);
        Abort::Error("0040");
    }

    public function put(Request $request,$id){
        $data = $request->json()->all();

        $this->product = Product::findOrFail($id);
        $options = $data["options"];

        $this->market_id = $this->product->market_id;
        $this->product->product_id = $data["marketProductId"];
        $this->product->category_id = $data["categoryId"];
        $this->product->division_id = $data["divisionId"];
        $this->product->sector_id_0 = $data["sector"][0];
        $this->product->sector_id_1 = isset($data["sector"][1]) ? $data["sector"][1] : NULL;
        $this->product->sector_id_2 = isset($data["sector"][2]) ? $data["sector"][2] : NULL;
        $this->product->market_id = $data["marketId"];
        $this->product->brand_id = $this->getBrandId($data["brand"]);
        $this->product->original_title = $data["title"]["origin"];
        $this->product->chinese_title = $data["title"]["zh"];
        $this->product->original_description = $data["description"]['origin'];
        $this->product->chinese_description = $data["description"]['zh'];
        $this->product->weight = $data["weight"];
        $this->product->price = $data["price"];
        $this->product->domestic_delivery_price = $data["deliveryPrice"];
        $this->product->is_free_delivery = $data["isFreeDelivery"];
        $this->product->stock = $data["stock"];
        $this->product->safe_stock = $data["safeStock"];
        $this->product->url = $data["url"];
        $this->product->status_code = $this->statusUpdate($request,$request['statusCode']);
        $this->product->end_date = Carbon::parse($data["endDate"])->timezone(config('app.timezone'))->toDateTimeString();


        if ( !$this->product->save() ) Abort::Error("0040");
        if ( $this->updateOptions($data['options']) ) return response()->success($this->product);
        Abort::Error("0040");
    }

    public function delete(Request $request,$id){
        $this->product = Product::findOrFail($id);
        if($this->product->delete()){
            return response()->success();
        }else {
            Abort::Error('0040');
        }
    }
    public function status(Request $request,$status_name){
        $status = Status::whereenglish_name($status_name)->firstOrFail();
        $products = $request['products'];
        foreach( $products as $value ){
            $this->product = Product::findOrFail($value);
            $this->product->status_code = $this->statusUpdate($request,$status['code']);
            $this->product->save();
        }
        return response()->success();
    }
    public function statusUpdate($request,$status_code){
        if( !$this->isSameStatus($status_code) ){
            $this->statusPermissionCheck($request);
            $this->forConfirm($status_code);
            return $status_code;
        }
        return $this->product->status_code;
    }
    public function forConfirm($status_code){
        if( $status_code == '0301' ){
            $this->startDateUpdate();
            return true;
        }
        return false;
    }
    private function statusPermissionCheck($request){
        $user = $this->getUserByTokenRequestOrFail($request);
        if ($user->grade == "superAdmin" || $user->grade == "admin"){
            return true;
        }
        Abort::Error("0043", "Can not change status");
    }
    private function isSameStatus($status_code){
        if( $this->product->status_code == $status_code ){
            return true;
        }
        return false;
    }
    private function startDateUpdate(){
        $this->product->start_date = Carbon::now()->toDateTimeString();
    }
    private function getBrandId($brand){
        return is_null($brand['origin'])
            ? null
            : Brand::firstOrCreate(array(
                "original_name" => $brand['origin'],
                "chinese_name" => $brand['zh'],
            ))->id;
    }

//    private function getCategoryId($category){
//        return is_array($category)
//        ? Category::firstOrCreate(
//            array(
//                "original_name" => $category["origin"],
//                "chinese_name" => $category["zh"],
//            ))["id"]
//        : Category::findOrFail($category)->value("id");
//    }
//    private function getDivisionId($division){
//        return is_array($division)
//        ? Division::firstOrCreate(
//            array(
//                "parent_id" => $this->product->category_id,
//                "original_name" => $division["origin"],
//                "chinese_name" => $division["zh"],
//            ))["id"]
//        : Division::findOrFail($division)->value("id");
//    }
//    private function getSectorId($division){
//        return is_array($division)
//            ? Sector::firstOrCreate(
//                array(
//                    "parent_id" => $this->product->category_id,
//                    "market_id" => $this->market_id,
//                    "market_category_id" => $this->market_category_id,
//                    "original_name" => $division["origin"],
//                    "chinese_name" => $division["zh"],
//                ))["id"]
//            : Sector::findOrFail($division)->value("id");
//    }
}
