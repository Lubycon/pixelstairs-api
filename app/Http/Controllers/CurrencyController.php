<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use GuzzleHttp\Client;
use League\Flysystem\Exception;

class CurrencyController extends Controller
{
    public $client;

    public function get(Request $request){
        try{
            $this->client = new Client();
            $response = $this->client->request('GET', "https://finance.yahoo.com/webservice/v1/symbols/allcurrencies/quote?format=json" , [
            ])->getBody()->getContents();
        }catch(\Exception $e){
            Abort::Error('0070',$e->getMessage());
        }

        $decodeResult = json_decode($response);
        if( is_null($decodeResult) ){
            $decodeResult = json_decode(gzdecode($response));
        }
        return response()->success($decodeResult);
    }
}
