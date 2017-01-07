<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Carbon\Carbon;
use Log;

use App\Models\Brand;
use App\Models\Product;
use App\Models\Option;
use App\Models\Sku;
use Abort;

class ProductController extends Controller
{
    public $product_id;
    public $market_id;

    public function post(Request $request){
        $data = $request->json()->all();

        $this->product_id = $data['id'];
        $this->market_id = $data['marketId'];

        $product = new Product;
        $product->product_id = $data['id'];
        // $product->category_id = $data['id'];
        // $product->division_id = $data['id'];
        // $product->sector_id = $data['id'];
        $product->market_id = $data['marketId'];
        $product->brand_id = is_null($data['brandName']) ? null : Brand::firstOrCreate(['name' => $data['brandName']])->id;
        $product->original_title = $data['title']['origin'];
        $product->korean_title = $data['title']['ko'];
        $product->english_title = $data['title']['en'];
        $product->chinese_title = $data['title']['zh'];
        $product->description = $data['description'];
        $product->price = $data['price'];
        $product->domestic_delivery_price = $data['deliveryPrice'];
        $product->is_free_delivery = $data['isFreeDelivery'];
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
                "korean_name" => $option['name']['ko'],
                "english_name" => $option['name']['en'],
                "price" => $option['price'],
                "stock" => $option['stock'],
                "safe_stock" => $option['safeStock'],
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
        Log::info(Sku::create($sku));
        $id = Sku::create($sku)->id;
        return $id;
    }
}
