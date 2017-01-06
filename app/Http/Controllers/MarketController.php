<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Abort;
use Log;
use GuzzleHttp\Client;

use App\Models\Market;

class MarketController extends Controller
{
    public $client;
    public $url;
    public $market;
    public $product_number;

    public function __construct(){
        $this->client = new Client();
    }


    public function get(Request $request){
        $query = $request->query();
        $this->market = Market::wherecode($query['marketId'])->first();
        $this->url = $query['url'];

        $parse_array = parse_url($this->url);
        parse_str($parse_array['query'], $query_parse);
        $this->product_number = $this->getProductNumber($query_parse);


        $response = $this->requsetBy11st($this->product_number);
        $responseXml = $this->getXmlOnBody($response);

        if ( $this->checkError($responseXml) ) Abort::Error('0040');

        $product_data = $this->xmlToJson($responseXml);
        $bindData = $this->bindXml($product_data);

        return response()->success($bindData);


    }

    public function bindXml($product_data){
        return $data = [
            'id' => $product_data['Product']['ProductCode'],
            'name' => $product_data['Product']['ProductName'],
            'priceInfo' => (object)array(
                'price' => $this->splitWon($product_data['Product']['ProductPrice']['Price']),
                'lowestPrice' => $this->splitWon($product_data['Product']['ProductPrice']['LowestPrice']),
            ),
            'deliveryPrice' => $this->splitWon($product_data['Product']['ShipFee']),
            'options' => $this->bindOption( $product_data ),
        ];
    }

    public function splitWon($value){
        $explode = explode('원',$value);
        $result = str_replace(",","", $explode[0]);
        return $result;
    }

    public function bindOption($option){
        if ( !isset($option['ProductOption']) ) return NULL;
        $optionList = $option['ProductOption']['OptionList']['Option']['ValueList']['Value'];
        $recodeList = [];
        foreach ($optionList as $key => $value) {
            $optionList[$key]['Price'] = $this->splitWon($value['Price']);
        }
        return $optionList;
    }

    public function getProductNumber($query_array){
        $product_number_name = 'prdNo';
        return $query_array[$product_number_name];
    }

    public function xmlToJson($xml){
        $json = json_encode($xml);
        $array = json_decode($json,TRUE);

        return $array;
    }
    public function requsetBy11st($product_number){
        $response = $this->client->request('GET', 'http://openapi.11st.co.kr/openapi/OpenApiService.tmall', [
            'query' => [
                'key' => '079b465d19c823b1582f605532755f3c',
                'apiCode' => 'ProductInfo',
                'productCode' => $product_number,
                'option' => 'SemiReviews,PdOption'
            ]
        ])->getBody()->getContents();

        return $response;
    }
    public function getXmlOnBody($response){
        return simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA);
    }
    public function checkError($responseXml){
        $check = isset($responseXml->ErrorCode);
        return $check;
    }
}
