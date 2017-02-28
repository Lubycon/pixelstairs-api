<?php

namespace App\Classes;

use Storage;
use Abort;
use Log;

use GuzzleHttp\Client;
use League\Flysystem\Exception;

class Currency
{
    private $client;
    private $url = "https://finance.yahoo.com/webservice/v1/symbols/allcurrencies/quote?format=json";

    public function __construct(){
        $this->client = new Client();
    }
    private function request(){
        try{
            $response = $this->client->request('GET', $this->url , [
            ])->getBody()->getContents();
        }catch(\Exception $e){
            Abort::Error('0070',$e->getMessage());
        }
        $decodeResult = $this->toJson($response);
        return $decodeResult;
    }
    private function toJson($response){
        $decodeResult = json_decode($response);
        if( is_null($decodeResult) ){
            $decodeResult = json_decode(gzdecode($response));
        }
        return $decodeResult;
    }
    public function getResult(){
        $request = $this->request();
        return $request;
    }
}