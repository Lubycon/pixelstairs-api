<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Carbon\Carbon;
use Log;

use App\Models\Product;

class ProductController extends Controller
{
    public function post(Request $request){
        $data = $request->json()->all();

        $product = new Product;
        $product->product_id = $data['id'];
        // $product->category_id = $data['id'];
        // $product->division_id = $data['id'];
        // $product->sector_id = $data['id'];
        $product->market_id = $data['marketId'];
        $product->brand_id = $data['brandName'];
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





        $product->save();

        return response()->success($product);

    }
}
