<?php
namespace App\Traits;

use GuzzleHttp\Client;
use Log;
use App\Models\Market;
use App\Models\Category;
use App\Models\Division;
use App\Models\Status;
use App\Models\Brand;
use App\Models\Option;

trait HaitaoRequestTraits{

    public $client;

    public function __construct(){
        $this->client = new Client();
    }

    public function productSale($product){
        $api_path = 'mitty.api/v1/products';
        $response = $this->client->request('GET', $api_path, [
            'form_params' => [
//                "mittyProductId" => $product["id"],
//                "marketProductId" => $product["product_id"],
//                "market" => Market::wherecode($product["market_id"])->value("name"),
//                "category" => Category::find($product["category_id"])["chinese_name"],
//                "division" => Division::find($product["division_id"])["chinese_name"],
//                "sector" => $product->sectorsDetailZh(),
//                "title" => $product["chinese_title"],
//                "brand" => is_null($product["brand_id"]) ? NULL : Brand::find($product["brand_id"])->value("chinese_name"),
//                "description" => $product["chinese_description"],
//                "weight" => $product["weight"],
//                "price" => $product["price"] + $product["domestic_delivery_price"],
//                "stock" => $product["stock"] - $product["safe_stock"],
//                "thumbnailUrl" => $product["thumbnail_url"],
//                "url" => $product["url"],
//                "status" => Status::wherecode($product["status_code"])->value("chinese_name"),
//                "startDate" => $product["start_date"],
//                "endDate" => $product["end_date"],
//                "options" => $this->bindOptionZh(Option::whereproduct_id($product["id"])->get())
            ]
        ])->getBody()->getContents();
        return $response;
    }
}
?>