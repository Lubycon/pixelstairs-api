<?php

namespace App\Http\Controllers;

use App\Models\SectionMarketInfo;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Abort;
use Log;
use GuzzleHttp\Client;

use App\Models\Category;
use App\Models\Division;
use App\Models\Section;
use App\Models\Market;

use App\Classes\Snoopy;
//use PHPHtmlParser\Dom;



class MarketController extends Controller
{
    public $client;
    public $dom;

    public $url;
    public $market;
    public $product_number;
    public $category_number;

    public $category_data;

    public function __construct(){
        $this->client = new Client();
//        $this->dom = new Dom;
    }

//    /**
//     * @SWG\Get(
//     *     path="/market",
//     *     summary="Get Openmarket Data",
//     *     description="get openmarket data from url",
//     *     operationId="get_market",
//     *     produces={"application/json"},
//     *     tags={"market"},
//     *     @SWG\Parameter(
//     *         name="marketId",
//     *         in="query",
//     *         description="",
//     *         required=true,
//     *         type="array",
//     *         @SWG\Items(
//     *             type="string",
//     *             enum={"0100", "0101", "0102"},
//     *             default="0100"
//     *         ),
//     *         collectionFormat="multi"
//     *     ),
//     *     @SWG\Parameter(
//     *         name="url",
//     *         in="query",
//     *         description="",
//     *         required=true,
//     *         type="string",
//     *         default="http://deal.11st.co.kr/product/SellerProductDetail.tmall?method=getSellerProductDetail&prdNo=1648381925&trTypeCd=38&trCtgrNo=947548"
//     *     ),
//     *     @SWG\Response(
//     *         response=200,
//     *         description="successful operation",
//     *     ),
//     *     @SWG\Response(
//     *         response="400",
//     *         description="Unexpected data value",
//     *     )
//     * )
//     */

    public function getBySnoopy(Request $request){
//        11st
//        $snoopy = new Snoopy;
//
//        $snoopy->fetch("http://deal.11st.co.kr/product/SellerProductDetail.tmall?method=getSellerProductDetail&prdNo=1254155722&trTypeCd=22&trCtgrNo=895019");
//        $source = $snoopy->results;
//        $res = iconv("euc-kr","UTF-8",$source);
//        print_r($res);

        $query = $request->query();
        $this->market = Market::wherecode($query['marketId'])->first();
        $this->url = urldecode($query['url']);
        ob_start();
        passthru("/usr/bin/python3 ".app_path()."/python/crawling.py $this->url");
        $output = ob_get_clean();
        echo $output;
    }

//    coupang function

//    coupang function











    public function get(Request $request){
        $query = $request->query();
        $this->market = Market::wherecode($query['marketId'])->first();
        $this->url = urldecode($query['url']);

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
            'thumbnail_url' => $product_data['Product']['BasicImage'],
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
            $sections = SectionMarketInfo::wheremarket_category_id($this->category_data['Category']['CategoryCode'])->get();
            if(isset($sections[0])){
                foreach( $sections as $key => $value ){
                    $result['ours']['sections'][] = $value->section['id'];
                }
            $division = Division::findOrFail($sections[0]->section['parent_id']);
            $category = Category::findOrFail($division['parent_id']);
            $result['ours']['divisionId'] = $division['id'];
            $result['ours']['categoryId'] = $category['id'];
            }
        }


        return $result;
    }

    public function splitWon($value){
        $explode = explode('원',$value);
        $result = str_replace(",","", $explode[0]);
        return (int)$result;
    }

    public function bindOption($option){
        if ( !isset($option['ProductOption']) ) return NULL;
        $valueList = $option['ProductOption']['OptionList']['Option']['ValueList'];
        $optionList = $valueList['Value'];
        $recodeList = [];


        if ( isset($optionList['Order']) ) {
            $recodeList[] = $this->setOptionArray($optionList);
        }else{
            foreach ($optionList as $key => $value) {
                $recodeList[] = $this->setOptionArray($value);
            }
        }
        return $recodeList;
    }

    public function setOptionArray($option){
        return array(
            "order" => $option['Order'],
            'price' => $this->splitWon($option['Price']),
            'valueName' => $option['ValueName'],
        );
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
