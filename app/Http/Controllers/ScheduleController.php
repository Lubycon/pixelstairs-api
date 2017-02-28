<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\Order;
use Carbon\Carbon;

use Log;
use Abort;

class ScheduleController extends Controller
{
    public $order;
    public $option;

    public function expireOrders(){
        $this->order = Order::where('order_status_code','0310')->
        where( 'payment_create_time','<',Carbon::now()->addMinutes(30)->toDateTimeString() )->get();

        foreach($this->order as $key => $value){
            $value->returnStock();
        }

        return response()->success($this->order);
    }
}
