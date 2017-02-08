<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Abort;
use Log;

use App\Models\Market;
use App\Models\Product;

use App\Crawlers\CoupangCrawler;

use App\Traits\OptionControllTraits;

use Slack;


class MarketController extends Controller
{
    use OptionControllTraits;

    public $url;
    public $market;

    public function __construct(){
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

//    public function getBySnoopy(Request $request){
////        11st
////        $snoopy = new Snoopy;
////        $snoopy->fetch("http://deal.11st.co.kr/product/SellerProductDetail.tmall?method=getSellerProductDetail&prdNo=1254155722&trTypeCd=22&trCtgrNo=895019");
////        $source = $snoopy->results;
////        $res = iconv("euc-kr","UTF-8",$source);
////        print_r($res);
//
//        $query = $request->query();
//        $this->market = Market::wherecode($query['marketId'])->first();
//        $this->url = urldecode($query['url']);
//        ob_start();
//        passthru("/usr/bin/python3 ".app_path()."/python/crawling.py $this->url");
//        $market_data = json_decode(ob_get_clean());
//
//        $crawlClass = new CoupangCrawler($market_data);
//
//        return response()->success($crawlClass->getResult());
//    }


    public function get(Request $request){
        $query = $request->query();

        if(!isset($query['marketId'])) Abort::Error('0040',"Can not search Querystring 'marketId'");
        if(!isset($query['url'])) Abort::Error('0040',"Can not search Querystring 'url'");

        $this->market = Market::wherecode($query['marketId'])->first();
        $this->url = urldecode($query['url']);

        if( $this->market['code'] == '0103' ){
            $crawlClass = new CoupangCrawler($this->url);
            return response()->success($crawlClass->getResult());
        }else{
            Abort::Error('0040',"This Market Code Not Allow");
        }

//        11st
//        $parse_array = parse_url($this->url);
//        parse_str($parse_array['query'], $query_parse);
//        $this->product_number = $this->getProductNumber($query_parse);
//        $this->category_number = $this->getCategoryNumber($query_parse);
//
//        $productRequest = $this->requsetOpenApi('product');
//        $productXml = $this->getXmlOnBody($productRequest);
//
//        if (!is_null($this->category_number)) {
//            $categoryRequest = $this->requsetOpenApi('category');
//            $categoryXml = $this->getXmlOnBody($categoryRequest);
//            if ( $this->checkError($categoryXml) ) Abort::Error('0040');
//            $this->category_data = $this->xmlToJson($categoryXml);
//        }
//
//        if ( $this->checkError($productXml) ) Abort::Error('0040');
//
//        $product_data = $this->xmlToJson($productXml);
//        $bindData = $this->bindXml($product_data);
//
//        return response()->success($bindData);
    }



//
//    public function bindXml($product_data){
//        $category_data = $this->getCategoryData();
//        return $data = [
//            'id' => $product_data['Product']['ProductCode'],
//            'name' => $product_data['Product']['ProductName'],
//            'category' => array(
//                "id" => $category_data['market_category_id'],
//                "name" => $category_data['market_category_name'],
//                "ours" => $category_data['ours'],
//            ),
//            'priceInfo' => (object)array(
//                'price' => $this->splitWon($product_data['Product']['ProductPrice']['Price']),
//                'lowestPrice' => $this->splitWon($product_data['Product']['ProductPrice']['LowestPrice']),
//            ),
//            'deliveryPrice' => $this->splitWon($product_data['Product']['ShipFee']),
//            'thumbnail_url' => $product_data['Product']['BasicImage'],
//            'options' => $this->bindOption( $product_data ),
//        ];
//    }
//
//    public function getCategoryData(){
//        $result = array(
//            'market_category_id' => $this->category_data['Category']['CategoryCode'],
//            'market_category_name' => $this->category_data['Category']['CategoryName'],
//            'ours' => null,
//        );
//        if(!is_null($this->category_data)){
//            $sections = SectionMarketInfo::wheremarket_category_id($this->category_data['Category']['CategoryCode'])->get();
//            if(isset($sections[0])){
//                foreach( $sections as $key => $value ){
//                    $result['ours']['sections'][] = $value->section['id'];
//                }
//            $division = Division::findOrFail($sections[0]->section['parent_id']);
//            $category = Category::findOrFail($division['parent_id']);
//            $result['ours']['divisionId'] = $division['id'];
//            $result['ours']['categoryId'] = $category['id'];
//            }
//        }
//
//
//        return $result;
//    }
//
//    public function splitWon($value){
//        $explode = explode('원',$value);
//        $result = str_replace(",","", $explode[0]);
//        return (int)$result;
//    }
//
//    public function bindOption($option){
//        if ( !isset($option['ProductOption']) ) return NULL;
//        $valueList = $option['ProductOption']['OptionList']['Option']['ValueList'];
//        $optionList = $valueList['Value'];
//        $recodeList = [];
//
//
//        if ( isset($optionList['Order']) ) {
//            $recodeList[] = $this->setOptionArray($optionList);
//        }else{
//            foreach ($optionList as $key => $value) {
//                $recodeList[] = $this->setOptionArray($value);
//            }
//        }
//        return $recodeList;
//    }
//
//    public function setOptionArray($option){
//        return array(
//            "order" => $option['Order'],
//            'price' => $this->splitWon($option['Price']),
//            'valueName' => $option['ValueName'],
//        );
//    }
//
//    public function getProductNumber($query_array){
//        $product_number_name = 'prdNo';
//        return $query_array[$product_number_name];
//    }
//    public function getCategoryNumber($query_array){
//        $category_name = ['dispCtgrNo','mCtgrNo','lCtgrNo','trCtgrNo'];
//        foreach ($category_name as $key => $value) {
//            if ( isset($query_array[$value]) ) return $query_array[$value];
//        }
//    }
//
//    public function xmlToJson($xml){
//        $json = json_encode($xml);
//        $array = json_decode($json,TRUE);
//
//        return $array;
//    }
//    public function requsetOpenApi($kind){
//        $response = $this->client->request('GET', 'http://openapi.11st.co.kr/openapi/OpenApiService.tmall', [
//            'query' => $this->apiSetting($kind)
//        ])->getBody()->getContents();
//
//        return $response;
//    }
//    public function apiSetting($kind){
//        switch ($kind) {
//            case 'product':
//            $result = [
//                'key' => '079b465d19c823b1582f605532755f3c',
//                'apiCode' => 'ProductInfo',
//                'productCode' => $this->product_number,
//                'option' => 'SemiReviews,PdOption'
//            ];
//            break;
//            case 'category':
//            $result = [
//                'key' => '079b465d19c823b1582f605532755f3c',
//                'apiCode' => 'CategoryInfo',
//                'categoryCode' => $this->category_number,
//            ];
//            break;
//
//            default: Abort::Error('0040'); break;
//        }
//        return $result;
//    }
//    public function getXmlOnBody($response){
//        return simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA);
//    }
//    public function checkError($responseXml){
//        $check = isset($responseXml->ErrorCode);
//        return $check;
//    }

    public function updateStock(Request $request,$product_id)
    {
        $product = Product::findOrFail($product_id);

        $this->market = $product->market;
        $this->url = urldecode($product->url);

        if ($this->market['code'] == '0103') {
            $crawlClass = new CoupangCrawler($this->url);
            $crawlData = $crawlClass->getResult();

            $productOption = $product->option;
            $newOption = $crawlData['options'];
            foreach( $productOption as $key => $value ){
                if( $value->translateName->original != $newOption[$key]['name'] ) Abort::Error('0040','diffrent option name!');
                $productOption[$key]['price'] = $newOption[$key]['price'];
                if( !$product->isLimited ){
                    $productOption[$key]['stock'] = $newOption[$key]['stock'];
                }
            }
            $this->updateStocks($product,$productOption);

            return response()->success($product);
        } else {
            Abort::Error('0040', "This Market Code Not Allow");
        }
    }

    public function updateScheduling(Request $request)
    {
        $products = Product::wherestatus_code('0301')->get();
        $successLog = [];
        $finishLog = [];
        $failLog = [];

        foreach( $products as $value ) {
            $update = $this->getUpdateStockResponse($request, $value);

            Log::info("processing " . $value->id);
            if( $update == "finish" ) {
                $finishLog[] = ["id" => $value->id];
                $value->status_code = "0302";
                $value->save();
            }else{
                $update = $update->getData();
                if( $update->status->code == "0000" ){$successLog[] = ["id" => $value->id];
                }else if( $update->status->code == "0040" ){$failLog[] = ["id" => $value->id,"code" => "0040"];
                }else{$failLog[] = ["id" => $value->id,"code" => "9999"];}
            }

            sleep(mt_rand(3,10));
        }

        Slack::to('#product_update_log')->enableMarkdown()->send(
            'success update = '.json_encode($successLog).
            'finish sale = '.json_encode($finishLog).
            'failed update = '.json_encode($failLog)
        );

        return response()->success($products);
    }

    private function getUpdateStockResponse($request,$value){
        try{
            return $this->updateStock($request,$value->id);
        }catch(\Exception $e){
            return "finish";
        }
    }


}
