<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Pager\PageController;

use App\Models\FreeGiftGroup;
use App\Models\Product;
use App\Models\Award;
use App\Models\User;
use Carbon\Carbon;


use Log;
use Abort;

use App\Http\Requests\FreeGift\FreeGiftGetRequest;
use App\Http\Requests\FreeGift\FreeGiftPostRequest;
use App\Http\Requests\FreeGift\FreeGiftWinnerPostRequest;

class FreeGiftController extends Controller
{
    protected $product;

    public function getList(FreeGiftGetRequest $request, $product_id){
        $this->language = $request->header('X-mitty-language');
        $query = $request->query();
        $query['search'] = "productId:".$product_id;
        $controller = new PageController('free_gift_group',$query);
        $collection = $controller->getCollection();

        $result = (object)array(
            "totalCount" => $controller->totalCount,
            "currentPage" => $controller->currentPage,
        );
        $freeGift = $collection[0];
        $totalStock = 0;
        foreach($freeGift->freeGift as $freeGiftOption){
            $result->options[] = (object)array(
                "id" => $freeGiftOption->id,
                "name" => $freeGiftOption->option->getTranslate($freeGiftOption->option),
                "stock" => $freeGiftOption->stock,
            );
            $totalStock += $freeGiftOption->stock;
        };
        $result->stockPerEach = $freeGift->stock_per_each;
        $result->firstDeployCount = $freeGift->first_deploy_count;
        $result->totalStock = $totalStock;

        if(!empty($result->options)){
            return response()->success($result);
        }else{
            return response()->success();
        }
    }

    public function post(FreeGiftPostRequest $request,$product_id){
        $this->product = Product::findOrFail($product_id);
        if($this->product->freeGiftGroup()->count() >= 1) Abort::Error('0052',"Already registered at product");
        $this->product->free_gift_group_id = $this->product->freeGiftGroup()->create([
            "product_id" => $this->product->id,
            "stock_per_each" => $request->stockPerEach,
        ])['id'];
        $this->product->product_status_code = "0303";
        $this->product->save();
        $this->product->freeGiftGroup->freeGift()->saveMany($this->product->freeGiftGroup->createGroupObject($request->options));
        return response()->success($this->product->freeGiftGroup);
    }

    public function winnerPost(FreeGiftWinnerPostRequest $request,$user_id){
        $this->product = Product::findOrFail($request->productId);
        $user = User::findOrFail($user_id);
        $freeGiftGroup = $this->product->freeGiftGroup;

        if( is_null($freeGiftGroup) ) Abort::Error('0040',"This product has not free gift");
        $freeGiftOption = $freeGiftGroup->freeGift()->findOrFail($request->freeGiftOptionId);
        if($freeGiftOption->stock < $request->stock) Abort::Error("0040","This Product has not enough stock");
        $freeGiftOption->stock = $freeGiftOption->stock - $request->stock;
        $freeGiftOption->save();
        $award = Award::create([
            "product_id" => $request->productId,
            "sku" => $freeGiftOption->option->sku,
            "user_id" => $user->id,
            "target" => $request->target,
            "give_stock" => $request->stock,
            "expire_date" => Carbon::now()->addDays(30),
        ]);

        return response()->success([
            "awardId" => $award['id'],
            "stock" => $freeGiftOption->stock,
        ]);
    }
}
