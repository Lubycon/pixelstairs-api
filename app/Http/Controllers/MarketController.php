<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Abort;
use Log;
use GuzzleHttp\Client;

use App\Models\Category;
use App\Models\Division;
use App\Models\Sector;
use App\Models\Market;

class MarketController extends Controller
{
    public $client;
    public $url;
    public $market;
    public $product_number;
    public $category_number;

    public $category_data;

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
        $this->category_number = $this->getCategoryNumber($query_parse);

        $productRequest = $this->requsetOpenApi('product');
        $productXml = $this->getXmlOnBody($productRequest);

        if (!is_null($this->category_number)) {
            $categoryRequest = $this->requsetOpenApi('category');
            $categoryXml = $this->getXmlOnBody($categoryRequest);
            if ( $this->checkError($categoryXml) ) Abort::Error('0040');
            $this->category_data = $this->xmlToJson($categoryXml);
        }

        if ( $this->checkError($productXml) ) Abort::Error('0040');

        $product_data = $this->xmlToJson($productXml);
        $bindData = $this->bindXml($product_data);

        return response()->success($bindData);
    }

    public function bindXml($product_data){
        $category_data = $this->getCategoryData();
        return $data = [
            'id' => $product_data['Product']['ProductCode'],
            'name' => $product_data['Product']['ProductName'],
            'category' => array(
                "id" => $category_data['market_category_id'],
                "name" => $category_data['market_category_name'],
                "ours" => $category_data['ours'],
            ),
            'priceInfo' => (object)array(
                'price' => $this->splitWon($product_data['Product']['ProductPrice']['Price']),
                'lowestPrice' => $this->splitWon($product_data['Product']['ProductPrice']['LowestPrice']),
            ),
            'deliveryPrice' => $this->splitWon($product_data['Product']['ShipFee']),
            'options' => $this->bindOption( $product_data ),
        ];
    }

    public function getCategoryData(){
        $result = array(
            'market_category_id' => $this->category_data['Category']['CategoryCode'],
            'market_category_name' => $this->category_data['Category']['CategoryName'],
            'ours' => null,
        );
        if(!is_null($this->category_data)){
            $sectors = Sector::wheremarket_category_id($this->category_data['Category']['CategoryCode'])->get();
            if(isset($sectors[0])){
                foreach( $sectors as $key => $value ){
                    $result['ours']['sectors'][] = $value['id'];
                }
            $division = Division::findOrFail($sectors[0]['parent_id']);
            $category = Category::findOrFail($division['parent_id']);
            $result['ours']['division'] = $division['id'];
            $result['ours']['category'] = $category['id'];
            }
        }


        return $result;
//        return array(
//            "category" => !is_null($this->category_data) ? $this->category_data['Category']['CategoryCode'] : null,
//            "division" => !is_null($this->category_data) ? $this->category_data['Category']['CategoryName'] : null,
//            "sector" => Sector::wheremarket_category_id($this->category_data['Category']['CategoryCode'])->get(),
//        );
    }

    public function splitWon($value){
        $explode = explode('원',$value);
        $result = str_replace(",","", $explode[0]);
        return $result;
    }

    public function bindOption($option){
        if ( !isset($option['ProductOption']) ) return NULL;
        $valueList = $option['ProductOption']['OptionList']['Option']['ValueList'];
        $optionList = $valueList['Value'];
        $recodeList = [];


        if ( isset($optionList['Order']) ) {
            $recodeList['order'] = $optionList['Order'];
            $recodeList['price'] = $this->splitWon($optionList['Price']);
            $recodeList['valueName'] = $optionList['ValueName'];
        }else{
            foreach ($optionList as $key => $value) {
                $recodeList[$key]['order'] = $value['Order'];
                $recodeList[$key]['price'] = $this->splitWon($value['Price']);
                $recodeList[$key]['name'] = $value['ValueName'];
            }
        }
        return $recodeList;
    }

    public function getProductNumber($query_array){
        $product_number_name = 'prdNo';
        return $query_array[$product_number_name];
    }
    public function getCategoryNumber($query_array){
        $category_name = ['dispCtgrNo','mCtgrNo','lCtgrNo','trCtgrNo'];
        foreach ($category_name as $key => $value) {
            if ( isset($query_array[$value]) ) return $query_array[$value];
        }
    }

    public function xmlToJson($xml){
        $json = json_encode($xml);
        $array = json_decode($json,TRUE);

        return $array;
    }
    public function requsetOpenApi($kind){
        $response = $this->client->request('GET', 'http://openapi.11st.co.kr/openapi/OpenApiService.tmall', [
            'query' => $this->apiSetting($kind)
        ])->getBody()->getContents();

        return $response;
    }
    public function apiSetting($kind){
        switch ($kind) {
            case 'product':
            $result = [
                'key' => '079b465d19c823b1582f605532755f3c',
                'apiCode' => 'ProductInfo',
                'productCode' => $this->product_number,
                'option' => 'SemiReviews,PdOption'
            ];
            break;
            case 'category':
            $result = [
                'key' => '079b465d19c823b1582f605532755f3c',
                'apiCode' => 'CategoryInfo',
                'categoryCode' => $this->category_number,
            ];
            break;

            default: Abort::Error('0040'); break;
        }
        return $result;
    }
    public function getXmlOnBody($response){
        return simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA);
    }
    public function checkError($responseXml){
        $check = isset($responseXml->ErrorCode);
        return $check;
    }
}
