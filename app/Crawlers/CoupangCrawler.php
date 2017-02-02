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

class CoupangCrawler
{
    private $snoopy;
    private $dom;

    private $product_id;
    private $item_id;
    private $vendor_id;

    private $result;

    //"productId": "11243305",
    //"data_vendor_item_id": "3075639772",
    //"data_item_id": "48593437"

    public function __construct($idInfo){
        $this->snoopy = new Snoopy;
        $this->dom = new Dom;

        $this->product_id = $idInfo->productId;
        $this->item_id = $idInfo->data_item_id;
        $this->vendor_id = $idInfo->data_vendor_item_id;

        $basicProductInfo = $this->basicProductInfo(); // product basic infomation
        $basicProductInfWithStock = $this->basicProductInfWithStock(); // product basic infomation with stock
        $vendorProductInfo = $this->vendorProductInfo(); // product require info + product detail images
        $optionData = $this->optionData(); // product require info + product detail images
        $productAtf = $this->productAtf(); // product title


        $this->result = [
            "id" => $this->product_id,
            "name" => $productAtf['name'],
            "category" => [],
            "priceInfo" => [
                "price" => $basicProductInfWithStock['price'],
                "lowestPrice" => 0
            ],
            "deliveryPrice" => $basicProductInfWithStock['deliveryPrice'],
            "thumbnailUrl" => $optionData['options'][0]["thumbnailUrl"],
            "optionKey" => $optionData['optionKey'],
            "options" => $optionData['options'],
            "original" => [
                "name" => $productAtf['name'],
                "basicInfo" => $basicProductInfo,
                "requireInfo" => $vendorProductInfo['requireInfo'],
                "detailImage" => $vendorProductInfo['detailImage'],
                "basicProductInfWithStock" => $basicProductInfWithStock,
                "options" => $optionData['options'],
                "optionKey" => $optionData['optionKey'],
            ],
        ];
    }

    protected  function productAtf(){
        $requestUrl = "https://www.coupang.com/vp/products/$this->product_id/product-atf?itemId=$this->item_id&vendorItemId=$this->vendor_id";
        $dom = $this->getDomResult($requestUrl);
        return [
            "name" => $this->getText($dom,'.prod-buy-header__title'),
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
    protected function optionData(){
        $requestUrl = "https://www.coupang.com/vp/products/$this->product_id/loadOptions?itemId=$this->item_id&vendorItemId=$this->vendor_id&&noAttribute=false";
        $dom = $this->getDomResult($requestUrl);
        $optionKey[] = $this->getText($dom,'.prod-option-name__button');
        $options = $this->getElement($dom,'.prod-option-select__item');
        $optionResult = [];
        foreach( $options as $value ){
            $optionResult[] = [
                "name" => $value->getAttribute('data-option-title'),
                "thumbnailUrl" => $value->getAttribute('data-option-img-src'),
            ];
        }

        return [
            "optionKey" => $optionKey,
            "options" => $optionResult,
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


    public function getResult(){
        return $this->result;
    }

}