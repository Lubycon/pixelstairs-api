<?php
/**
 * Created by PhpStorm.
 * User: daniel_zepp
 * Date: 2017. 2. 2.
 * Time: 오후 2:26
 */

namespace App\Crawlers;

use App\Models\SectionMarketInfo;
use App\Models\Division;
use App\Models\Category;

use App\Classes\Snoopy;
use Illuminate\Foundation\Testing\HttpException;
use PHPHtmlParser\Dom;
use Log;
use Abort;
use PHPHtmlParser\Exceptions\EmptyCollectionException;

class ElevenStreetCrawler
{
    private $snoopy;
    private $dom;

    private $product_id;

    private $result;


    public function __construct($url){
        $idInfo = $this->getProductId($url);

        $this->snoopy = new Snoopy;
        $this->dom = new Dom;

        if( is_null($idInfo) ) Abort::Error("0056","Can Not Found Product Information in Url");
        $this->product_id = $idInfo !== "" ? $idInfo : Abort::Error('0056') ;

        $dom = $this->getDom($url);


        Log::info(dd($dom->find('.heading h2')->text));


//
//        $basicProductInfo = $this->basicProductInfo(); // product basic infomation
//        $basicProductInfWithStock = $this->basicProductInfWithStock(); // product basic infomation with stock
//        $vendorProductInfo = $this->vendorProductInfo(); // product require info + product detail images
//        $optionCollection = '';
//        $optionSkuList = '';
//        if( $this->sdp_style == "NORMAL" ){
//            $optionSkuList = $this->pagedOption(0); // product require info + product detail images
////            $optionSkuList = $this->loadOptions(); // product require info + product detail images
//        }else if( $this->sdp_style == "FASHION_STYLE_TWO_WAY" ){
//            $optionCollection = $this->optionAttribute(); // product require info + product detail images
//            $optionSkuList = $this->optionSkuList($optionCollection);
//        }else{
//            Abort::Error('0040',"Product option type not allowed");
//        }
//        $productAtf = $this->productAtf(); // product title
//        $categories = $this->categories(); // product title


        $this->result = [
            "id" => (string)$this->product_id,
            "title" => (string)2222,
            "priceInfo" => [
                "price" => (int)33333,
                "lowestPrice" => (int)4444,
            ],
            "deliveryPrice" => (int)5555,
            "options" => 66666,
            "brand" => (string)77,
            "manufacturerCountryId" => "",
            "seller" => [
                "name" => '',
                "rate" => '',
            ],
            "category" =>[
                "id" => '1',
                "name" => 22,
                "ours" => 123,
            ],
            "isLimited" => 123,
            "detailImages" => 123,
            "description" => 123,
            "thumbnailUrl" => 123,
        ];




//        $this->result = [
//            "id" => (string)$this->product_id,
//            "title" => (string)$productAtf['productName'],
//            "priceInfo" => [
//                "price" => (int)$basicProductInfWithStock['price'],
//                "lowestPrice" => (int)$basicProductInfWithStock['price'],
//            ],
//            "deliveryPrice" => (int)$basicProductInfWithStock['deliveryPrice'],
//            "options" => $optionSkuList,
//            "brand" => (string)$productAtf['brandName'],
//            "manufacturerCountryId" => "",
//            "seller" => [
//                "name" => "coupang",
//                "rate" => 4.5,
//            ],
//            "category" =>[
//                "id" => $categories['market_section']['id'],
//                "name" => $categories['market_section']['name'],
//                "ours" => $categories['ours'],
//            ],
//            "isLimited" => is_null($this->maxBuyAble) ? false : true,
//            "detailImages" => $vendorProductInfo['detailImage'],
//            "description" => (string)$basicProductInfo.(string)$vendorProductInfo['requireInfo'],
//            "thumbnailUrl" => $productAtf["thumbnailUrl"],
//        ];
    }

        private function getProductId($url){
            $parse_array = parse_url($url);
            parse_str($parse_array['query'], $query_parse);
            return $this->getProductNumber($query_parse);
        }
        public function getProductNumber($query_array){
            $product_number_name = 'prdNo';
            return $query_array[$product_number_name];
        }

        public function getDom($url){
            $requestUrl = $url;
            $dom = $this->getDomResult($requestUrl);

            return $dom;
        }



//
//    private function optionAttribute(){
//        $requestUrl = "$this->coupangUrl/vp/products/$this->product_id/brandsdp/attributes?itemId=$this->item_id&noAttribute=false&sdpStyle=FASHION_STYLE_TWO_WAY";
//        $dom = $this->getDomResult($requestUrl);
//        $eachOption = $dom->find('.each-prod-option');
//        $result = [];
//        foreach( $eachOption as $key => $value ){ // option depth
//            $result[] = [
//                "key" => $value->getAttribute('data-type-name'),
//                "values" => $this->getEachOptionAttributes($value),
//            ];
//        }
//        return $result;
//    }
//    private function getEachOptionAttributes($option){
//        $optionValue = $option->find('.prod-option-grid__item');
//        $result = [];
//        foreach( $optionValue as $key => $value ){
//            $result[] = [
//                "optionKey" => $value->getAttribute('data-option-key'),
//                "optionValue" => $value->getAttribute('data-option-value'),
//                "thumbnailUrl" => $value->getAttribute('data-option-img-src'),
//                "displayType" => $value->getAttribute('data-display-type'),
//            ];
//        }
//        return $result;
//    }
//    protected function optionSkuList($optionCollection){
//        $result = [];
//        $matchAttr = $this->splitAttrKey($optionCollection[1]['values'][0]['optionKey']);
//        foreach( $optionCollection[0]['values'] as $key => $value ){
//            $searchAttr = $this->splitAttrKey($value['optionKey']);
//            $requestUrl = "$this->coupangUrl/vp/products/$this->product_id/brandsdp/options/0?attrTypeIds=$matchAttr->current&noAttribute=false&sdpStyle=FASHION_STYLE_TWO_WAY&selectedAttrTypeIds=$searchAttr->current&selectedAttrValueIds=$searchAttr->remote";
//            $dom = json_decode($this->getDomResult($requestUrl));
//
//            foreach( $dom->options as $eachOption ){
//                $result[] = [
//                    "order" => (int)$key,
//                    "price" => (int)$this->splitWon($eachOption->salesPrice),
//                    "name" => (string)$eachOption->title,
//                    "stock" => (int)$eachOption->remainCount,
//                    "isSoldout" => (bool)$eachOption->impendSoldOut,
//                    "thumbnailUrl" => [
//                        "file" => is_null($eachOption->imageUrl) ? NULL : (string)$eachOption->imageUrl->displayImageUrl,
//                        "index" => 0,
//                    ],
//                ];
//            }
//        }
//        return $result;
//    }
////    protected function loadOptions(){
////        $requestUrl = "$this->coupangUrl/vp/products/$this->product_id/loadOptions?itemId=$this->item_id&vendorItemId=$this->vendor_id&&noAttribute=false";
////        $dom = $this->getDomResult($requestUrl);
////        $optionKey[] = $this->getText($dom,'.prod-option-name__button');
////        $options = $this->getElement($dom,'.prod-option-select__item');
////        $optionResult = [];
////        foreach( $options as $key => $value ){
////            $detailUrl = $value->getAttribute('data-request-uri');
////            if( $detailUrl != '' ){ // option has more request
////                $parseUrl = '$this->coupangUrl'.str_replace("amp;",'',$detailUrl);
////                $detailDom = $this->getDomResult($parseUrl);
////                $getElement = $this->getElement($detailDom,'.prod-option-select__item');
////                $justPrice = (int)$this->splitWon($this->getText($getElement,'.prod-txt-small'));
////            }
////            if( $this->isAllowOptionTitle($value->getAttribute('data-option-title')) ){ // exception
////                $optionResult[] = [
////                    "order" => (int)$key,
////                    "price" => isset($justPrice) ? $justPrice : (int)$this->splitWon($this->getText($value,'.prod-txt-small')),
////                    "name" => (string)$value->getAttribute('data-option-title'),
////                    "stock" => (int)$this->maxBuyAble,
////                    "isSoldout" => (bool)strpos($value->getAttribute('class'),'soldout'),
////                    "thumbnailUrl" => [
////                        "file" => (string)$value->getAttribute('data-option-img-src'),
////                        "index" => 0,
////                    ],
////                ];
////            }
////        }
////        return $optionResult;
////    }
//    protected function pagedOption($pageNumber){
//        $requestUrl = "$this->coupangUrl/vp/products/$this->product_id/paged-options?page=$pageNumber";
//        $dom = $this->getDomResult($requestUrl);
//        $nextPage = $this->getElementAttribute($dom,'.next-url','data-next-url');
//        $nextNumber = $pageNumber + 1;
//        $options = $this->getElement($dom,'.prod-option-select__item');
//        foreach( $options as $key => $value ){
//            $detailUrl = $value->getAttribute('data-request-uri');
//            if( $detailUrl != '' ){ // option has more request
//                $parseUrl = $this->coupangUrl.str_replace("amp;",'',$detailUrl);
//                $detailDom = $this->getDomResult($parseUrl);
//                $getElement = $this->getElement($detailDom,'.prod-option-select__item');
//                $justPrice = (int)$this->splitWon($this->getText($getElement,'.prod-txt-small'));
//            }
//            $this->optionResult[] = [
//                "order" => (int)$key,
//                "price" => isset($justPrice) ? $justPrice : (int)$this->splitWon($this->getText($value,'.prod-txt-small')),
//                "name" => (string)$value->getAttribute('data-option-title'),
//                "stock" => (int)$this->maxBuyAble,
//                "isSoldout" => (bool)strpos($value->getAttribute('class'),'soldout'),
//                "thumbnailUrl" => [
//                    "file" => is_null($value->getAttribute('data-option-img-src')) == "" ? NULL : $value->getAttribute('data-option-img-src'),
//                    "index" => 0,
//                ],
//            ];
//        }
//        if( $nextPage != '' ) $this->pagedOption($nextNumber);
//        return $this->optionResult;
//    }
//
//
//
//    protected function categories(){
//        $requestUrl = "$this->coupangUrl/vp/products/$this->product_id/breadcrumb-gnbmenu";
//        $dom = $this->getDomResult($requestUrl);
//        $breadcrumb = $dom->find('#breadcrumb .breadcrumb-link');
//        $category = [];
//        foreach( $breadcrumb as $value ){
//            $category[] = [
//                "id" => (string)$this->getLastSegment($value->getAttribute('href')),
//                "name" => (string)$value->text,
//            ];
//        }
//        return [
//            "market_section" => end($category)['name'] == '쿠팡 홈' ? null : end($category),
//            "ours" => $this->getCategoryData(end($category)),
//        ];
//    }
//    protected function productAtf(){
//        $requestUrl = "$this->coupangUrl/vp/products/$this->product_id/product-atf?itemId=$this->item_id&vendorItemId=$this->vendor_id";
//        $dom = $this->getDomResult($requestUrl);
//        return [
//            "brandName" => $this->getText($dom,'.prod-brand-name'),
//            "productName" => $this->getText($dom,'.prod-buy-header__title'),
//            "thumbnailUrl" => [
//                "file" => $this->getElement($dom,'.prod-image__detail')->getAttribute('data-src'),
//                "index" => 0,
//            ],
//        ];
//    }
//    protected function basicProductInfo(){
//        $requestUrl = "$this->coupangUrl/vp/products/$this->product_id/vendor-items/$this->vendor_id/selling-infos?itemId=$this->item_id";
//        $this->snoopy->fetchtext($requestUrl);
//        $dom = $this->snoopy->results;
//        return $dom;
//    }
//    protected function basicProductInfWithStock(){
//        $requestUrl = "$this->coupangUrl/vp/products/$this->product_id/vendor-items/$this->vendor_id/sale-infos/sdp";
//        $dom = $this->getDomResult($requestUrl);
//        $this->maxBuyAble = $this->getText($dom,'.prod-buyable-quantity');
//        return [
//            "isSoldOut" => $this->getElementAttribute($dom,'.prod-value-holder','data-is-sold-out'),
//            "deliveryPrice" => $this->getElementAttribute($dom,'.prod-value-holder','data-shipping-fee') == ""
//                ? 0
//                : $this->getElementAttribute($dom,'.prod-value-holder','data-shipping-fee'),
//            "price" => $this->getElementAttribute($dom,'.prod-value-holder','data-sale-price'),
//            "totalStock" => $this->getElementAttribute($dom,'.prod-value-holder','data-stock-quantity'),
//        ];
//    }
//    protected function vendorProductInfo(){
//        $requestUrl = "$this->coupangUrl/vp/products/$this->product_id/vendor-items/$this->vendor_id?isFixedVendorItem=true&type=sdp";
//        $dom = $this->getDomResult($requestUrl);
//        $requireInfo = $this->getMergeText($dom,'.prod-item-attr-name');
//        $optionsImg = $this->getImageSrc($dom,'.lazy-img','data-src');
//        return [
//            "requireInfo" => (string)$requireInfo,
//            "detailImage" => $optionsImg,
//        ];
//    }
//
//
    public function getDomResult($requestUrl){ //common
        $this->snoopy->fetch($requestUrl);
        $source = $this->snoopy->results;
        $source = iconv("EUC-KR","UTF-8",$source);
        $source = str_replace(array("\r\n","\r","\n","\t"),'',$source);
        return $this->dom->load($source);
    }
    public function getText($dom,$findWord){
        try{
            $requireDom = $dom->find($findWord)->text;
            return $requireDom;
        }catch(EmptyCollectionException $e){
            Log::info("$findWord Data not found");
            return null;
        }
    }
    public function getMergeText($dom,$findWord){
        $requireDom = $dom->find($findWord);
        $result = '';
        foreach ($requireDom as $value)
        {$result .= $value->text."\n";}
        return $result;
    }
    public function getElement($dom,$findAttr){
        $requireDom = $dom->find($findAttr);
        return $requireDom;
    }
    public function getElementAttribute($dom,$findAttr,$chooseAttr){
        $requireDom = $dom->find($findAttr)->getAttribute($chooseAttr);
        return $requireDom;
    }
    public function getImageSrc($dom,$findAttr,$chooseAttr){
        $requireDom = $dom->find($findAttr);

        $result = [];
        foreach ($requireDom as $key => $value)
        {
            $src = $value->getAttribute($chooseAttr);
            if( $src[0] == '/' ) $src = 'https:'.$src;
            $result[] = [
                "file" => (string)$src,
                "index" => $key,
            ];
        }
        return $result;
    }
//
//
//
//    protected function isAllowOptionTitle($value){
//        return !in_array($value,$this->optionTitleBlackList);
//    }
//    public function getLastSegment($url){
//        $segments = explode('/',$url);
//        return end($segments);
//    }
    public function splitWon($value){
        $explode = explode('원',$value);
        $result = str_replace(",","", $explode[0]);
        return (int)$result;
    }
//    public function splitAttrKey($attrTypeIds){
//        $split = explode(':',$attrTypeIds);
//        return (object)array(
//            "current" => $split[0],
//            "remote" => $split[1],
//        );
//    }
//    public function getCategoryData($marketCategory){
//        $result = [];
//        $sections = SectionMarketInfo::wheremarket_category_id($marketCategory['id'])->get();
//        if(isset($sections[0])){
//            foreach( $sections as $key => $value ){
//                $result['sections'][] = (string)$value->section['id'];
//            }
//            $division = Division::findOrFail($sections[0]->section['parent_id']);
//            $category = Category::findOrFail($division['parent_id']);
//            $result['divisionId'] = (string)$division['id'];
//            $result['categoryId'] = (string)$category['id'];
//        }else{
//            $result = null;
//        }
//        return $result;
//    }


    public function getResult(){
        return $this->result;
    }
}