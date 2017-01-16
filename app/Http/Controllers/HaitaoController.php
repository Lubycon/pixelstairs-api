<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Product;
use App\Models\Market;
use App\Models\Category;
use App\Models\Division;
use App\Models\Status;
use App\Models\Brand;
use App\Models\Option;

use Carbon\Carbon;

use App\Traits\OptionControllTraits;

class HaitaoController extends Controller
{
    use OptionControllTraits;


    /**
     * @SWG\Get(
     *     path="/haitao/product/{product_id}",
     *     summary="Get Product Detail",
     *     description="Get Product Detail via Mitty Product ID",
     *     produces={"application/json"},
     *     tags={"Product"},
     *     @SWG\Parameter(
     *         name="product_id",
     *         default="501",
     *         in="path",
     *         description="product's data you want item id",
     *         required=true,
     *         type="integer"
     *     ),
     *     @SWG\Response(
     *         response=200,
     *         description="successful operation",
     *     ),
     *     @SWG\Response(
     *         response="400",
     *         description="Unexpected data value",
     *     )
     * )
     */

    public function productGet(Request $request,$product_id){
        $product = Product::findOrFail($product_id);

        $response = (object)array(
            "mittyProductId" => $product["id"],
            "marketProductId" => $product["product_id"],
            "haitaoProductId" => "haitao present",
            "market" => Market::wherecode($product["market_id"])->value("name"),
            "category" => Category::find($product["category_id"])["chinese_name"],
            "division" => Division::find($product["division_id"])["chinese_name"],
            "sector" => $product->sectorsDetailZh(),
            "title" => $product["chinese_title"],
            "brand" => is_null($product["brand_id"]) ? NULL : Brand::find($product["brand_id"])->value("chinese_name"),
            "description" => $product["chinese_description"],
            "price" => $product["price"] + $product["domestic_delivery_price"],
            "stock" => $product["stock"] - $product["safe_stock"],
            "url" => $product["url"],
            "status" => Status::wherecode($product["status_code"])->value("chinese_name"),
            "startDate" => $product["start_date"],
            "endDate" => $product["end_date"],
            "options" => $this->bindOptionZh(Option::whereproduct_id($product["id"])->get())
        );
        return response()->success($response);
    }

    public function productStore(Request $request){
        $product = Product::findOrFail($request['product_id']);

        $response = (object)array(
            "mittyProductId" => $product["id"],
            "marketProductId" => $product["product_id"],
            "market" => Market::wherecode($product["market_id"])->value("name"),
            "category" => Category::find($product["category_id"])["chinese_name"],
            "division" => Division::find($product["division_id"])["chinese_name"],
            "sector" => $product->sectorsDetailZh(),
            "title" => $product["chinese_title"],
            "brand" => is_null($product["brand_id"]) ? NULL : Brand::find($product["brand_id"])->value("chinese_name"),
            "description" => $product["chinese_description"],
            "price" => $product["price"] + $product["domestic_delivery_price"],
            "stock" => $product["stock"] - $product["safe_stock"],
            "url" => $product["url"],
            "status" => Status::wherecode($product["status_code"])->value("chinese_name"),
            "startDate" => $product["start_date"],
            "endDate" => $product["end_date"],
            "options" => $this->bindOptionZh(Option::whereproduct_id($product["id"])->get())
        );
        return response()->success($response);
    }

    public function orderPost(Request $request){
        $order = new Order;

        $order->haitao_order_id = $request['order_id'];
        $order->haitao_user_id = $request['user_id'];
        $order->quantity = $request['quantity'];
        $order->sku = $request['sku'];

        if(!$order->save()) Abor::Error('0040','Check Request');

        return response()->success($order);
    }
}
