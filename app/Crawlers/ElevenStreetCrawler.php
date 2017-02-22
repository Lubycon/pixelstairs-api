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
//use PHPHtmlParser\Dom;

use IvoPetkov\HTML5DOMDocument;

use Log;
use Abort;
use PHPHtmlParser\Exceptions\EmptyCollectionException;

class ElevenStreetCrawler
{
    private $snoopy;
    private $dom;

    private $product_id;

    private $result;

    private $marketUrl = "http://www.11st.co.kr/";


    public function __construct($url){
        $idInfo = $this->getProductId($url);

        $this->snoopy = new Snoopy;
        $this->dom = new HTML5DOMDocument();

        if( is_null($idInfo) ) Abort::Error("0056","Can Not Found Product Information in Url");
        $this->product_id = $idInfo !== "" ? $idInfo : Abort::Error('0056') ;
        $this->dom = $this->getDom($url);





        $last_category = $this->lastCategory($this->dom);
        $title = $this->getInnerHtml($this->getElement($this->dom,'title'));
        $priceInfo = $this->priceInfo($this->dom);
        $deliveryPrice = $this->deliveryPrice($this->dom);
        $sellerInfo = $this->sellerInfo($this->dom);
        $product_detail = $this->productDetail($this->dom);
        $options = $this->options($this->dom);
        $thumbnail_image = $this->thumbnailImage($this->dom);
//        $product_detail_image = $this->productDetailImage($this->dom);

        $this->result = [
            "id" => (string)$this->product_id,
            "title" => (string)$title,
            "priceInfo" => [
                "price" => (int)$this->splitWon( $priceInfo['price'] ),
                "lowestPrice" => (int)$this->splitWon( $priceInfo['lowestPrice'] ),
            ],
            "deliveryPrice" => (int)$deliveryPrice,
            "options" => $options,
            "brand" => (string)$product_detail['brandName'],
            "manufacturerCountryId" => "", // can not
            "seller" => [
                "name" => $sellerInfo['name'],
                "rate" => $sellerInfo['rate'],
            ],
            "category" => $last_category,
            "isLimited" => false,
            "detailImages" => null, // will be
            "description" => null, // can not
            "thumbnailUrl" => $thumbnail_image,
        ];
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


        private function priceInfo($dom){
            $priceBox = $this->getElement($dom,'.price_detail');
            $original_price = $this->getInnerHtml($this->getElement($priceBox,'.normal_price s'));
            $sale_price = $this->getInnerHtml($this->getElement($priceBox,'.sale_price'));
            return [
                "price" => $original_price,
                "lowestPrice" => $sale_price,
            ];
        }

        private function deliveryPrice($dom){
            $boxes = $this->getElementAll($dom,'.det_info');
            $deliveryBox = null;
            foreach($boxes as $key => $value){
                $attr = $value->getAttributes();
                if( isset($attr['name']) && $attr['name'] == 'dlvCstInfoView' ) $deliveryBox = $value;
            }
            $deliveryPrice = $this->getInnerHtml($this->getElement($deliveryBox,'.row .col'));
            $deliveryPrice = $this->splitDeliveryInfo($deliveryPrice);
            $deliveryPrice = $this->splitWon($deliveryPrice);
            return $deliveryPrice;
        }

        private function splitDeliveryInfo($deliveryPrice){
            $explode = explode('배송비 : ',$deliveryPrice);
            return (int)$explode[1] == '무료' ? 0 : $explode[1];
        }

        private function sellerInfo($dom){
            $sellerBox = $this->getElement($dom,'.seller_info');
            $sellerRate = $this->getInnerHtml($this->getElement($sellerBox,'.selr_star_b'));
            $sellerRate = $this->getSellerRate($sellerRate);
            return [
                "name" => $this->getInnerHtml($this->getElement($sellerBox,'.seller_nickname')),
                "rate" => $sellerRate,
            ];
        }

        private function getSellerRate($sellerRate){
            if( is_null($sellerRate) ) return 4.5;
            $explode = explode('판매자 평점 별5개 중 ',$sellerRate);
            $explode = explode('개',$explode[1]);
            $value = $explode[0];
            return (int)$value/100*5;
        }

        private function productDetail($dom){
            $detailTable = $this->getElement($dom,'.prdc_detail_table');
            $detailTableTh = $this->getElementAll($detailTable,'th');
            $brandCell = null;
            foreach($detailTableTh as $key => $value){
                $text = $this->getInnerHtml($value);
                if( $text == '브랜드' ) {
                    $brandCell = $value->nextSibling->nextSibling;
                }
            }
            return [
                "brandName" => trim($brandCell->nodeValue),
            ];
        }

        private function productDetailImage($dom){
            $detailTable = $this->getElement($dom,'.prdc_bo_detail .ifrm_bbs');
            $iframeAttr = $detailTable->getAttributes();
            dd($iframeAttr);
        }

        private function lastCategory($dom){
            $categoryArea = $this->getElement($dom,'.location_wrap');
            $categories = $this->getElementAll($categoryArea,'.loca_cate_wrap');

            if( count($categories) == 0 ){
                $script = $this->getElement($categoryArea,'script');
                $scriptToArray = $this->javascriptStringParse($this->getInnerHtml($script));
                $sectionId = end($scriptToArray['DealSelCtgr']);
                foreach( $scriptToArray['DealNaviSCtgr']->DATA as $key => $value ){
                    if( $value->DISP_CTGR_NO == $sectionId ){
                        $sectionName = $value->DISP_CTGR_NM;
                    }
                }
                if( is_null($sectionId) || is_null($sectionName) ) Abort::Error("0070","Can not found Category Information");
            }else{
                $sectionId = $this->getElement(end($categories),'input')->getAttributes()['value'];
                $sectionName = $this->getSectionName($this->getElement(end($categories),'button'));
            }
            $section = [
                "id" => (string)$sectionId,
                "name" => $sectionName,
                "ours" => $this->getCategoryData(["id"=>$sectionId]),
            ];
            return $section;
        }

        private function thumbnailImage($dom){
            $thumbnail = $this->getElement($dom,'.thumbBox img')->getAttributes();
            return [
                "file" => $thumbnail['src'],
                "index" => 0,
            ];
        }

        private function getSectionName($element){
            $text = $this->getInnerHtml($element);
            $text = preg_replace("/<(.*)/","",$text);
            return trim($text);
        }

        private function options($dom){
            $optionArea = $this->getElement($dom,'.option_wrap_layer');
            $allOptions = $this->getElementAll($optionArea,'.ui_option_list'); // depth catch
            $optionDepth = count($allOptions);
            $firstOptions = $this->getElementAll(reset($allOptions),'a'); // depth catch
            $result = [];
            foreach($firstOptions as $key => $value){
                $attr = $value->getAttributes();
                $parentOption = [
                    "order" => $key,
                    "price" => (int)$attr['data-price'],
                    "name" => $attr['data-dtloptnm'],
                    "stock" => (int)$attr['data-stckqty'],
                    "isSoldout" => (int)$attr['data-stckqty'] == 0 ? true : false,
                    "thumbnailUrl" => null,
                ];

                if( $optionDepth > 1 ) {
                    $optionInfo = [
                        "depth" => $optionDepth > 2 ? "Sub" : "Last",
                        "level" => 2,
                        "number_array" => $attr['data-optno'],
                        "total_depth" => $optionDepth,
                    ];
                    $deepOption = $this->getDeepOption($optionInfo);
                    foreach ($deepOption as $optionKey => $optionValue) {
                        $result[] = [
                            "order" => $parentOption['order'],
                            "price" => $parentOption['price'] + $optionValue['price'],
                            "name" => $parentOption['name'] . ',' . $optionValue['name'],
                            "stock" => $optionValue['stock'],
                            "isSoldout" => $parentOption['isSoldout'] || $optionValue['isSoldout'],
                            "thumbnailUrl" => null,
                        ];
                    }
                }else{
                    $result[] = $parentOption;
                }
            }
            return $result;
        }

        private function getOptionData($info){
            $requestUrl = $this->marketUrl."product/SellerProductDetailAjax.tmall?method=getProductDetail".$info['depth']."OptionList&prdNo=".$this->product_id."&optNoArr=".$info['number_array']."&optLvl=".$info['level']."&selOptCnt=".$info['total_depth']."&isNewOption=true";
            $dom = $this->getJsonDomResult($requestUrl);
            $bodyText = substr($dom, 1, -1);
            $json = json_decode($bodyText);
            return $json->infoList;
        }

        private function getDeepOption($info){
            $json = $this->getOptionData($info);
            $lastJson = null;
            $result = [];
            foreach( $json as $main_key => $main_value ){
                $readyResult = [ // ready 2 level option
                    "name" => $main_value->dtlOptNm,
                    "price" => isset($main_value->price) ? (int)$main_value->price : (int)0,
                    "stock" => $main_value->stckQty,
                    "isSoldout" => (int)$main_value->stckQty == 0 ? true : false,
                ];
                if( $info['depth'] != 'Last' ){ // if 3depth option
                    $lastInfo = ["depth" => "Last","level" => 3,"number_array" => $info["number_array"].','.$main_value->optNo,"total_depth"=>$info['total_depth']];
                    $lastJson = $this->getOptionData($lastInfo);
                    foreach( $lastJson as $key => $value ){
                        $result[] = [
                            "name" => $readyResult['name'].','.$value->dtlOptNm,
                            "price" => (int)$readyResult['price'] + (int)$value->price,
                            "stock" => $value->stckQty,
                            "isSoldout" => $readyResult['isSoldout'] || (int)$value->stckQty == 0 ? true : false,
                        ];
                    }
                }else{
                    $result[] = $readyResult;
                }
            }
            return $result;
        }

    public function getDomResult($requestUrl){
        $this->snoopy->fetch($requestUrl);
        $source = $this->snoopy->results;
        $source = iconv("EUC-KR","UTF-8",$source);
        $source = str_replace(array("\r\n","\r","\n","\t"),'',$source);
        $dom = new HTML5DOMDocument();
        $dom->loadHTML($source);
        return $dom;
    }
    public function getJsonDomResult($requestUrl){
        $this->snoopy->fetch($requestUrl);
        $source = $this->snoopy->results;
        return $source;
    }

    public function getElement($dom,$querySelector){
        try{
            $selectors = explode(' ',$querySelector);

            foreach( $selectors as $key => $value ){
                if( is_null($dom) ) Abort::Error('0040','Go Catch');
                $dom = $dom->querySelector($value);
            }
            return $dom;
        }catch(\Exception $e){
            Log::info("$querySelector element not found");
            return null;
        }
    }
    public function getElementAll($dom,$querySelector){
        try{
            $selectors = explode(' ',$querySelector);
            foreach( $selectors as $key => $value ){
                $dom = $dom->querySelectorAll($value);
                if( is_null($dom) ) Abort::Error('0040','Go Catch');
            }
            return $dom;
        }catch(\Exception $e){
            Log::info("$querySelector element not found");
            return null;
        }
    }

    public function getInnerHtml($dom){
        try{
            $requireDom = $dom->innerHTML;
            return $requireDom;
        }catch(\Exception $e){
            Log::info("inner html not found");
            return null;
        }
    }

    public function splitWon($value){ // common
        $explode = explode('원',$value);
        $result = str_replace(",","", $explode[0]);
        return (int)$result;
    }
    public function getCategoryData($marketCategory){ //common
        $result = [];
        $sections = SectionMarketInfo::wheremarket_category_id($marketCategory['id'])->get();
        if(isset($sections[0])){
            foreach( $sections as $key => $value ){
                $result['sections'][] = (string)$value->section['id'];
            }
            $division = Division::findOrFail($sections[0]->section['parent_id']);
            $category = Category::findOrFail($division['parent_id']);
            $result['divisionId'] = (string)$division['id'];
            $result['categoryId'] = (string)$category['id'];
        }else{
            $result = null;
        }
        return $result;
    }

    private function javascriptStringParse($script){
        $script = str_replace('<script type="text/javascript">','',$script);
        $script = str_replace('</script>','',$script);
        $explode = explode(';',$script);

        $scriptToArray = [];
        foreach( $explode as $key => $value ){
            if( $value != '' ){
                $titlePattern = '/(?= )(.*)(?= = )/';
                preg_match($titlePattern, $value, $title);
                $title = trim($title[0]);
                $bodyPattern = "/(?={)(.*)(?=)/";
                preg_match($bodyPattern, $value, $body);
                $body = $body[0];
                if($title=="DealSelCtgr"){
                    $body=str_replace('CT','"CT',$body);
                    $body=str_replace('NO','NO"',$body);
                }
                $bodyToJson = json_decode(str_replace("'",'"',$body));
                $scriptToArray[$title] = $bodyToJson;
            }
        }
        return $scriptToArray;
    }


    public function getResult(){ //common
        return $this->result;
    }
}