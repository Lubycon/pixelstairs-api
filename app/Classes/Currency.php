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
    private $result;
    public $simpleResult;
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
        $this->result = $this->toJson($response);
        return $this->result;
    }
    public function exchangeToUsd($price,$currency){
        if( $currency == 'USD' ) return $price;
        $this->checkResult();
        return round($price / $this->simpleResult[$currency]['price'],2);
    }
    public function getExchangeRate($from,$to){
        if( $from != "USD" ) Abort::Error('0070','Unable Currency Unit');
        $this->checkResult();
        return round($this->simpleResult[$to],2);
    }
    private function toSimple($original){
        $data = $original->list->resources;
        $result = [];
        foreach( $data as $value ){
            $explode = explode('USD/',$value->resource->fields->name);
            if( count($explode) > 1 ){
                $result[$explode[1]] = [
                    'price' => $value->resource->fields->price,
                    'utc_time' => $value->resource->fields->utctime,
                ];
            }
        }
        $this->simpleResult = $result;
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
    private function checkResult(){
        if( is_null($this->result) ) $this->request();
        is_null($this->simpleResult)
            ? $this->toSimple($this->result)
            : $this->toSimple($this->result);
    }
}