<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\FreeGiftGroup;
use App\Models\Product;

use Log;
use Abort;

class FreeGiftController extends Controller
{
    protected $product;


    public function post(Request $request,$product_id){
        $this->product = Product::findOrFail($product_id);
        $this->product->freeGiftGroup()->create([
            "product_id" => $this->product->id,
            "stock_per_each" => $request->stockPerEach,
        ]);
        $this->product->freeGiftGroup->freeGift()->saveMany($this->product->freeGiftGroup->createGroupObject($request->options));
        return response()->success($this->product->freeGiftGroup);
    }
}
