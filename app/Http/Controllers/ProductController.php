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

use App\Models\Brand;
use App\Models\Product;
use App\Models\Option;
use App\Models\Sku;
use Abort;

class ProductController extends Controller
{
    public $product_id;
    public $market_id;

    public function get(Request $request){
        $query = $request->query();
        $controller = new PageController(null,$query);
        $collection = $controller->getCollection();

        $result = (object)array(
            "totalCount" => $controller->totalCount,
            "currentPage" => $controller->currentPage,
            "contents" => []
        );
        foreach($collection as $array){
            $result->products[] = (object)array(
                'id' => $array['product_id'],
                'haitaoId' => $array['haitao_product_id'],
                'title' => (object)array(
                    'origin' => $array['original_title'],
                    // 'ko' => $array['korean_title'],
                    // 'en' => $array['english_title'],
                    'zh' => $array['chinese_title'],
                ),
                'brandName' => is_null($array['brand_id']) ? NULL : Brand::findOrFail($array['brand_id']),

                'description' => $array['description'],
                'price' => $array['price'],
                'stock' => $array['stock'],
                'safeStock' => $array['safeStock'],
                'url' => $array['url'],
                'status_code' => $array['status_code'],
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

        $this->product_id = $data['id'];
        $this->market_id = $data['marketId'];

        $product = new Product;
        $product->product_id = $data['id'];
        $product->category_id = is_string($data['category']) ? Category::firstOrCreate(array("name"=>$data['category']))['id'] : Category::findOrFail($data['category'])->value('id');
        $product->division_id = is_string($data['division'])
            ? Division::firstOrCreate(
                array(
                    "parent_id" => $product->category_id,
                    "market_id" => $data['marketId'],
                    "market_category_id" => $data['marketDivisionId'],
                    "name" => $data['division'],
                ))['id']
            : Division::findOrFail($data['division'])->value('id');
        $product->market_id = $data['marketId'];
        $product->brand_id = is_null($data['brandName']) ? null : Brand::firstOrCreate(['name' => $data['brandName']])->id;
        $product->original_title = $data['title']['origin'];
        // $product->korean_title = $data['title']['ko'];
        // $product->english_title = $data['title']['en'];
        $product->chinese_title = $data['title']['zh'];
        $product->description = $data['description'];
        $product->price = $data['price'];
        $product->domestic_delivery_price = $data['deliveryPrice'];
        $product->is_free_delivery = $data['isFreeDelivery'];
        $product->stock = $data['stock'];
        $product->safe_stock = $data['safeStock'];
        $product->url = $data['url'];
        $product->status_code = '0300';
        $product->start_date = Carbon::now()->toDateTimeString();
        $product->end_date = $data['endDate'];
        Option::insert($this->setOption($data['options']));

        if ($product->save()) return response()->success($product);
        Abort::Error('0040');
    }

    private function setOption($options){
        $result = [];
        $index = 0;
        foreach ($options as $key => $option) {
            $result[] = array(
                "market_id" => $this->market_id,
                "product_id" => $this->product_id,
                "sku_id" => $this->createSku($option,$index),
                "original_name" => $option['name']['origin'],
                "chinese_name" => $option['name']['zh'],
                // "korean_name" => $option['name']['ko'],
                // "english_name" => $option['name']['en'],
                "price" => $option['price'],
                // "stock" => $option['stock'],
                // "safe_stock" => $option['safeStock'],
            );
            $index++;
        }
        return $result;
    }

    private function createSku($option,$index){
        $sku = array(
            "market_id" => $this->market_id,
            "product_id" => $this->product_id,
            "sku" => 'MK'.$this->market_id.'PD'.$this->product_id.'ID'.$index,
            "description" => $option['name']['origin'],
        );
        $id = Sku::create($sku)->id;
        return $id;
    }
}
