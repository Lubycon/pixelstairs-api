<?php
/**
 * Created by PhpStorm.
 * User: daniel_zepp
 * Date: 2017. 2. 2.
 * Time: 오후 2:26
 */

namespace App\Crawlers;

use App\Classes\Snoopy;
use PHPHtmlParser\Dom;
use Log;
use Abort;

class CoupangCrawler
{
    private $snoopy;
    private $dom;

    private $product_id;
    private $item_id;
    private $vendor_id;
    private $sdp_style;

    private $result;

    public function __construct($idInfo){
        $this->snoopy = new Snoopy;
        $this->dom = new Dom;

        Log::info($idInfo->product_id);

        if( is_null($idInfo) ) Abort::Error("0040","Unknown Product Url");
        $this->product_id = $idInfo->product_id;
        $this->item_id = $idInfo->data_item_id;
        $this->vendor_id = $idInfo->data_vendor_item_id;
        $this->sdp_style = $idInfo->data_sdp_style;

        $basicProductInfo = $this->basicProductInfo(); // product basic infomation
        $basicProductInfWithStock = $this->basicProductInfWithStock(); // product basic infomation with stock
        $vendorProductInfo = $this->vendorProductInfo(); // product require info + product detail images
        $optionCollection = '';
        $optionSkuList = '';
        if( $this->sdp_style == "NORMAL" ){
            $optionSkuList = $this->loadOptions(); // product require info + product detail images
        }else if( $this->sdp_style == "FASHION_STYLE_TWO_WAY" ){
            $optionCollection = $this->optionAttribute(); // product require info + product detail images
            $optionSkuList = $this->optionSkuList($optionCollection);
        }else{
            Abort::Error('0040',"Product option type not allowed");
        }
        $productAtf = $this->productAtf(); // product title
        $categories = $this->categories(); // product title

        $this->result = [
            "id" => $this->product_id,
            "name" => $productAtf['productName'],
            "section" => $categories['section'],
            "priceInfo" => [
                "price" => $basicProductInfWithStock['price'],
                "lowestPrice" => $basicProductInfWithStock['price'],
            ],
            "brand" => $productAtf['brandName'],
            "manufacturer" => "",
            "seller" => [
                "name" => "coupang",
                "rate" => "4.5",
            ],
            "deliveryPrice" => $basicProductInfWithStock['deliveryPrice'],
            "totalStock" => $basicProductInfWithStock['totalStock'],
            "maxBuyAble" => $basicProductInfWithStock['maxBuyAble'],
            "detailImages" => $vendorProductInfo['detailImage'],
            "description" => $basicProductInfo.$vendorProductInfo['requireInfo'],
            "thumbnailUrl" => $optionSkuList[0]["thumbnailUrl"],
//            "optionCollection" => $optionCollection,
            "options" => $optionSkuList,
        ];
    }

    private function optionAttribute(){
        $requestUrl = "https://www.coupang.com/vp/products/$this->product_id/brandsdp/attributes?itemId=$this->item_id&noAttribute=false&sdpStyle=FASHION_STYLE_TWO_WAY";
        $dom = $this->getDomResult($requestUrl);
        $eachOption = $dom->find('.each-prod-option');
        $result = [];
        foreach( $eachOption as $key => $value ){ // option depth
            $result[] = [
                "key" => $value->getAttribute('data-type-name'),
                "values" => $this->getEachOptionAttributes($value),
            ];
        }
        return $result;
    }
    private function getEachOptionAttributes($option){
        $optionValue = $option->find('.prod-option-grid__item');
        $result = [];
        foreach( $optionValue as $key => $value ){
            $result[] = [
                "optionKey" => $value->getAttribute('data-option-key'),
                "optionValue" => $value->getAttribute('data-option-value'),
                "thumbnailUrl" => $value->getAttribute('data-option-img-src'),
                "displayType" => $value->getAttribute('data-display-type'),
            ];
        }
        return $result;
    }
    protected function optionSkuList($optionCollection){
        $result = [];
        $matchAttr = $this->splitAttrKey($optionCollection[1]['values'][0]['optionKey']);
        foreach( $optionCollection[0]['values'] as $key => $value ){
            $searchAttr = $this->splitAttrKey($value['optionKey']);
            $requestUrl = "https://www.coupang.com/vp/products/$this->product_id/brandsdp/options/0?attrTypeIds=$matchAttr->current&noAttribute=false&sdpStyle=FASHION_STYLE_TWO_WAY&selectedAttrTypeIds=$searchAttr->current&selectedAttrValueIds=$searchAttr->remote";
            $dom = json_decode($this->getDomResult($requestUrl));

            foreach( $dom->options as $eachOption ){
                $result[] = [
                    "order" => $key,
                    "price" => $this->splitWon($eachOption->salesPrice),
                    "name" => $eachOption->title,
                    "stock" => $eachOption->remainCount,
                    "isSoldout" => $eachOption->impendSoldOut,
                    "thumbnailUrl" => $eachOption->imageUrl->displayImageUrl,
                ];
            }
        }
        return $result;
    }
    protected function loadOptions(){
        $requestUrl = "https://www.coupang.com/vp/products/$this->product_id/loadOptions?itemId=$this->item_id&vendorItemId=$this->vendor_id&&noAttribute=false";
        $dom = $this->getDomResult($requestUrl);
        $optionKey[] = $this->getText($dom,'.prod-option-name__button');
        $options = $this->getElement($dom,'.prod-option-select__item');
        $optionResult = [];
        foreach( $options as $key => $value ){
            if( $value->getAttribute('data-option-img-src') == "" ) Abort::Error('0040',"Product Options Each Thumbnail Not Exist");
            $optionResult[] = [
                "order" => $key,
                "price" => $this->splitWon($this->getText($value,'.prod-txt-small')),
                "name" => $value->getAttribute('data-option-title'),
                "stock" => 0,
                "isSoldout" => (bool)strpos($value->getAttribute('class'),'soldout'),
                "thumbnailUrl" => $value->getAttribute('data-option-img-src'),
            ];
        }
        return $optionResult;
    }
    protected function categories(){
        $requestUrl = "https://www.coupang.com/vp/products/$this->product_id/breadcrumb-gnbmenu";
        $dom = $this->getDomResult($requestUrl);
        $breadcrumb = $dom->find('#breadcrumb .breadcrumb-link');
        $category = [];
        foreach( $breadcrumb as $value ){
            $category[] = [
                "id" => $this->getLastSegment($value->getAttribute('href')),
                "name" => $value->text,
            ];
        }
        return [
            "section" => end($category),
        ];
    }
    protected function productAtf(){
        $requestUrl = "https://www.coupang.com/vp/products/$this->product_id/product-atf?itemId=$this->item_id&vendorItemId=$this->vendor_id";
        $dom = $this->getDomResult($requestUrl);
        return [
            "brandName" => $this->getText($dom,'.prod-brand-name'),
            "productName" => $this->getText($dom,'.prod-buy-header__title'),
        ];
    }
    protected function basicProductInfo(){
        $requestUrl = "https://www.coupang.com/vp/products/$this->product_id/vendor-items/$this->vendor_id/selling-infos?itemId=$this->item_id";
        $this->snoopy->fetchtext($requestUrl);
        $dom = $this->snoopy->results;
        return $dom;
    }
    protected function basicProductInfWithStock(){
        $requestUrl = "https://www.coupang.com/vp/products/$this->product_id/vendor-items/$this->vendor_id/sale-infos/sdp";
        $dom = $this->getDomResult($requestUrl);
        return [
            "isSoldOut" => $this->getElementAttribute($dom,'.prod-value-holder','data-is-sold-out'),
            "deliveryPrice" => $this->getElementAttribute($dom,'.prod-value-holder','data-shipping-fee') == ""
                ? 0
                : $this->getElementAttribute($dom,'.prod-value-holder','data-shipping-fee'),
            "price" => $this->getElementAttribute($dom,'.prod-value-holder','data-sale-price'),
            "totalStock" => $this->getElementAttribute($dom,'.prod-value-holder','data-stock-quantity'),
            "maxBuyAble" => $this->getText($dom,'.prod-buyable-quantity'),
        ];
    }
    protected function vendorProductInfo(){
        $requestUrl = "https://www.coupang.com/vp/products/$this->product_id/vendor-items/$this->vendor_id?isFixedVendorItem=true&type=sdp";
        $dom = $this->getDomResult($requestUrl);
        $requireInfo = $this->getMergeText($dom,'.prod-item-attr-name');
        $optionsImg = $this->getImageSrc($dom,'.lazy-img','data-src');
        return [
            "requireInfo" => $requireInfo,
            "detailImage" => $optionsImg,
        ];
    }




    public function getDomResult($requestUrl){
        $this->snoopy->fetch($requestUrl);
        $source = $this->snoopy->results;
        $source = str_replace(array("\r\n","\r","\n"),'',$source);
        return $this->dom->load($source);
    }
    public function getText($dom,$findWord){
        $requireDom = $dom->find($findWord)->text;
        return $requireDom;
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
        foreach ($requireDom as $value)
        {
            $src = $value->getAttribute($chooseAttr);
            if( $src[0] == '/' ) $src = 'https:'.$src;
            $result[] = $src;
        }
        return $result;
    }



    public function getLastSegment($url){
        $segments = explode('/',$url);
        return end($segments);
    }
    public function splitWon($value){
        $explode = explode('원',$value);
        $result = str_replace(",","", $explode[0]);
        return (int)$result;
    }
    public function splitAttrKey($attrTypeIds){
        $split = explode(':',$attrTypeIds);
        return (object)array(
            "current" => $split[0],
            "remote" => $split[1],
        );
    }


    public function getResult(){
        return $this->result;
    }

}