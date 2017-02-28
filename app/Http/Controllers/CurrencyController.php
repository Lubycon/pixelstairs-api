<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Classes\Currency;

class CurrencyController extends Controller
{
    public $currency;

    public function __construct(){
        $this->currency = new Currency();
    }

    public function get(Request $request){
        return response()->success($this->currency->getResult());
    }
}
