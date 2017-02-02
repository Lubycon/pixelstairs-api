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
        $vendorProductInfo = $this->vendorProductInfo(); // product require info + product detail images



        $this->result = [
            "basicInfo" => $basicProductInfo,
            "vendorInfo" => [
                "requireInfo" => $vendorProductInfo['requireInfo'],
            ],
            "detailImage" => $vendorProductInfo['detailImage']
        ];
    }

    protected function basicProductInfo(){
        $requestUrl = "https://www.coupang.com/vp/products/$this->product_id/vendor-items/$this->vendor_id/selling-infos?itemId=$this->item_id";
        $this->snoopy->fetchtext($requestUrl);
        $source = $this->snoopy->results;
        return $source;
    }

    protected function vendorProductInfo(){
        $requestUrl = "https://www.coupang.com/vp/products/$this->product_id/vendor-items/$this->vendor_id?isFixedVendorItem=true&type=sdp";
        $this->snoopy->fetch($requestUrl);
        $source = $this->snoopy->results;
        $source = str_replace(array("\r\n","\r","\n"),'',$source);
        $dom = $this->dom->load($source);
        $requireInfo = $this->getMergeText($dom,'.prod-item-attr-name');
        $optionsImg = $this->getImageSrc($dom,'.lazy-img','data-src');


        return [
            "requireInfo" => $requireInfo,
            "detailImage" => $optionsImg,
        ];
    }

    public function getMergeText($dom,$findWord){
        $requireDom = $dom->find($findWord);
        $result = '';
        foreach ($requireDom as $value)
        {$result .= $value->text."\n";}
        return $result;
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