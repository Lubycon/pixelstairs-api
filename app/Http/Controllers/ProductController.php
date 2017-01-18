<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Pager\PageController;

use Carbon\Carbon;
use Log;

use App\Models\Status;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Option;
use Abort;

use App\Traits\GetUserModelTrait;
use App\Traits\OptionControllTraits;
use App\Traits\HaitaoRequestTraits;
use App\Traits\StatusInfoTraits;

class ProductController extends Controller
{
    use GetUserModelTrait,OptionControllTraits,HaitaoRequestTraits,StatusInfoTraits;

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
                'origin' => is_null($product["brand_id"]) ? NULL : Brand::find($product["brand_id"])['original_name'],
                'zh' => is_null($product["brand_id"]) ? NULL : Brand::find($product["brand_id"])['chinese_name'],
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
            "thumbnailUrl" => $product["thumbnail_url"],
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
                "thumbnailUrl" => $array["thumbnail_url"],
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
        $this->product->thumbnail_url = $data["thumbnailUrl"];
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
        $this->product->thumbnail_url = $data["thumbnailUrl"];
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

    private function getBrandId($brand){
        return is_null($brand['origin'])
            ? null
            : Brand::firstOrCreate(array(
                "original_name" => $brand['origin'],
                "chinese_name" => $brand['zh'],
            ))->id;
    }
}
