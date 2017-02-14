<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Pager\PageController;

use App\Http\Requests\Order\OrderGetRequest;

use Log;

class OrderController extends Controller
{
    public function getList(OrderGetRequest $request){
        $query = $request->query();
        $controller = new PageController('order',$query);
        $collection = $controller->getCollection();

        $result = (object)array(
            "totalCount" => $controller->totalCount,
            "currentPage" => $controller->currentPage,
        );
        foreach($collection as $order){
            $result->orders[] = (object)array(
                "id" => $order['id'],
                "haitaoOrderId" => $order['haitao_order_id'],
                "haitaoUserId" => $order['haitao_user_id'],
                "product" => [
                    'id' => $order->product['id'],
                    "title" => $order->getTranslate($order->product),
                    'url' => $order->product['url']


                ],
                "sku" => $order->option['sku'],
                "quantity" => $order['quantity'],
                "orderStatusCode" => $order['order_status_code'],
                "orderDate" => $order['order_date'],
            );
        };
        if(!empty($result->orders)){
            return response()->success($result);
        }else{
            return response()->success();
        }
    }

    public function put(Request $request,$order_id){

    }
}
