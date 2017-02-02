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
use App\Crawlers\CoupangCrawler;
use PHPHtmlParser\Dom;



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
        $this->dom = new Dom;
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
//        $snoopy->fetch("http://deal.11st.co.kr/product/SellerProductDetail.tmall?method=getSellerProductDetail&prdNo=1254155722&trTypeCd=22&trCtgrNo=895019");
//        $source = $snoopy->results;
//        $res = iconv("euc-kr","UTF-8",$source);
//        print_r($res);

//        return $this->dom->load("'    <div class=\"product-item__detail\"         data-raw='{ \"vendorItemId\":3075639772 }'>                                    <div class=\"product-essential-info\">                    <div class=\"product-item__table\">                        <p class=\"table-title\">필수 표기정보</p>                        <ul>                                                            <li>                                    <span class=\"prod-item-attr-name\">용량(중량) : 0.5g</span>                                </li>                                                            <li>                                    <span class=\"prod-item-attr-name\">제품 주요 사양 : 모든 피부 사용</span>                                </li>                                                            <li>                                    <span class=\"prod-item-attr-name\">사용기한 또는 개봉 후 사용기간 : 제조일로부터 18개월 / 개봉후 6개월</span>                                </li>                                                            <li>                                    <span class=\"prod-item-attr-name\">사용방법 : 다이얼을 돌려 심을 적당히 빼내어 준 후 속눈썹 라인에 밀착시켜 눈매를 따라 자연스럽게 라인을 그려 줍니다.</span>                                </li>                                                            <li>                                    <span class=\"prod-item-attr-name\">제조업자 및 제조판매업자 : 코코 / 미팩토리</span>                                </li>                                                            <li>                                    <span class=\"prod-item-attr-name\">제조국 : 대한민국</span>                                </li>                                                            <li>                                    <span class=\"prod-item-attr-name\">주요성분 : 트리메칠실록시실리케이트,이소도데칸,사이클로펜타실록산,흑색산화철,폴리에칠렌,적색산화철,마이카,합성왁스,황색산화철,티타늄디옥사이드,세레신,칸데릴라왁스,폴리이소부텐</span>                                </li>                                                            <li>                                    <span class=\"prod-item-attr-name\">식품의약품안전처 심사 필 유무 : 해당사항없음</span>                                </li>                                                            <li>                                    <span class=\"prod-item-attr-name\">사용할 때 주의사항 : 1. 화장품을 사용하여 다음과 같은 이상이 있는 경우에는 사용을 중지하여야 하며, 계속 사용하면 증상이 악화되므로 피부과 전문의 등에게 상담할 것가) 사용 중 붉은 반점, 부어오름, 가려움증, 자극 등의 이상이 있는 경우나) 적용 부위가 직사광선에 의하여 위와 같은 이상이 있는 경우2. 상처가 있는 부위, 습진 및 피부염 등의 이상이 있는 부위에는 사용을 하지 말 것3. 보관 및 취급 시의 주의사항가) 개봉 후에는 제품이 건조되므로 즉시 사용하시고, 한번 사용한 제품은 다시 사용하지 말 것나) 유아•소아의 손이 닿지 않는 곳에 보관할 것다) 고온 또는 저온의 장소 및 직사광선이 닿는 곳에는 보관하지 말 것</span>                                </li>                                                            <li>                                    <span class=\"prod-item-attr-name\">품질보증기준 : 제품 이상 시 공정거래위원회 고시 소비자분쟁해결기준에 의거 보상합니다.</span>                                </li>                                                            <li>                                    <span class=\"prod-item-attr-name\">소비자상담관련 전화번호 : 쿠팡고객센터 1577-7011</span>                                </li>                                                    </ul>                    </div>                    <button class=\"product-essential-info__morebtn small-arrow-btn-bottom\">정보 더보기</button>                </div>                                    <div class=\"product-vendor-items\">                    <div class=\"product-vendor-items__inside\">                                                                <div class=\"vendor-item\">                                                            <div class=\"type-IMAGE_NO_SPACE\">                                                                            <div class=\"subType-IMAGE\">                                                                                                                                                <img class=\"lazy-img\" src=\"data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==\" data-src=\"//img1c.coupangcdn.com/image/product/content/vendorItem/2017/01/13/48593437/8a199e9d-8742-4100-83d9-e32036202463.jpg\"                                                     onerror=\"this.src='http://mimg1.coupangcdn.com/thumbnails/remote/622x622/image/coupang/common/no_img_1000_1000.png'\"                                                     width=\"100%\" alt=\"\"/>                                                                                                                                        </div>                                                                    </div>                                                            <div class=\"type-IMAGE_NO_SPACE\">                                                                            <div class=\"subType-IMAGE\">                                                                                                                                                <img class=\"lazy-img\" src=\"data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==\" data-src=\"//img4a.coupangcdn.com/image/product/content/vendorItem/2017/01/13/48593437/870e1f63-69dc-4e7d-92da-ab04e906869b.jpg\"                                                     onerror=\"this.src='http://mimg1.coupangcdn.com/thumbnails/remote/622x622/image/coupang/common/no_img_1000_1000.png'\"                                                     width=\"100%\" alt=\"\"/>                                                                                                                                        </div>                                                                    </div>                                                            <div class=\"type-IMAGE_NO_SPACE\">                                                                            <div class=\"subType-IMAGE\">                                                                                                                                                <img class=\"lazy-img\" src=\"data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==\" data-src=\"//img3a.coupangcdn.com/image/product/content/vendorItem/2017/01/13/48593437/2a1baa18-3bbc-4db7-8308-b475c0e9123c.jpg\"                                                     onerror=\"this.src='http://mimg1.coupangcdn.com/thumbnails/remote/622x622/image/coupang/common/no_img_1000_1000.png'\"                                                     width=\"100%\" alt=\"\"/>                                                                                                                                        </div>                                                                    </div>                                                            <div class=\"type-IMAGE_NO_SPACE\">                                                                            <div class=\"subType-IMAGE\">                                                                                                                                                <img class=\"lazy-img\" src=\"data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==\" data-src=\"//img4a.coupangcdn.com/image/product/content/vendorItem/2017/01/13/48593437/ae742267-1275-44e4-870c-c2edc5c14ec4.jpg\"                                                     onerror=\"this.src='http://mimg1.coupangcdn.com/thumbnails/remote/622x622/image/coupang/common/no_img_1000_1000.png'\"                                                     width=\"100%\" alt=\"\"/>                                                                                                                                        </div>                                                                    </div>                                                            <div class=\"type-IMAGE_NO_SPACE\">                                                                            <div class=\"subType-IMAGE\">                                                                                                                                                <img class=\"lazy-img\" src=\"data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==\" data-src=\"//img3a.coupangcdn.com/image/product/content/vendorItem/2017/01/16/48593437/ca8bc1e4-6649-4da7-9a4b-f6ce78576279.jpg\"                                                     onerror=\"this.src='http://mimg1.coupangcdn.com/thumbnails/remote/622x622/image/coupang/common/no_img_1000_1000.png'\"                                                     width=\"100%\" alt=\"\"/>                                                                                                                                        </div>                                                                    </div>                                                            <div class=\"type-IMAGE_NO_SPACE\">                                                                            <div class=\"subType-IMAGE\">                                                                                                                                                <img class=\"lazy-img\" src=\"data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==\" data-src=\"//img3a.coupangcdn.com/image/product/content/vendorItem/2017/01/16/48593437/46c96daa-32fe-48c0-8c1b-a7ce4a8a32d5.jpg\"                                                     onerror=\"this.src='http://mimg1.coupangcdn.com/thumbnails/remote/622x622/image/coupang/common/no_img_1000_1000.png'\"                                                     width=\"100%\" alt=\"\"/>                                                                                                                                        </div>                                                                    </div>                                                            <div class=\"type-IMAGE_NO_SPACE\">                                                                            <div class=\"subType-IMAGE\">                                                                                                                                                <img class=\"lazy-img\" src=\"data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==\" data-src=\"//img3a.coupangcdn.com/image/product/content/vendorItem/2017/01/13/48593437/17fa4771-82c6-42bc-a3ca-bf0cc377bf42.jpg\"                                                     onerror=\"this.src='http://mimg1.coupangcdn.com/thumbnails/remote/622x622/image/coupang/common/no_img_1000_1000.png'\"                                                     width=\"100%\" alt=\"\"/>                                                                                                                                        </div>                                                                    </div>                                                            <div class=\"type-IMAGE_NO_SPACE\">                                                                            <div class=\"subType-IMAGE\">                                                                                                                                                <img class=\"lazy-img\" src=\"data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==\" data-src=\"//img2c.coupangcdn.com/image/product/content/vendorItem/2017/01/13/48593437/a4830fd8-bbde-4b60-ba98-1094a36fa459.jpg\"                                                     onerror=\"this.src='http://mimg1.coupangcdn.com/thumbnails/remote/622x622/image/coupang/common/no_img_1000_1000.png'\"                                                     width=\"100%\" alt=\"\"/>                                                                                                                                        </div>                                                                    </div>                                                            <div class=\"type-IMAGE_NO_SPACE\">                                                                            <div class=\"subType-IMAGE\">                                                                                                                                                <img class=\"lazy-img\" src=\"data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==\" data-src=\"//img4a.coupangcdn.com/image/product/content/vendorItem/2017/01/13/48593437/84a7be66-8231-458d-9eeb-3d1b552ae076.jpg\"                                                     onerror=\"this.src='http://mimg1.coupangcdn.com/thumbnails/remote/622x622/image/coupang/common/no_img_1000_1000.png'\"                                                     width=\"100%\" alt=\"\"/>                                                                                                                                        </div>                                                                    </div>                                                            <div class=\"type-IMAGE_NO_SPACE\">                                                                            <div class=\"subType-IMAGE\">                                                                                                                                                <img class=\"lazy-img\" src=\"data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==\" data-src=\"//img4a.coupangcdn.com/image/product/content/vendorItem/2017/01/13/48593437/e6e518cb-c572-4858-b74f-e100795a2164.jpg\"                                                     onerror=\"this.src='http://mimg1.coupangcdn.com/thumbnails/remote/622x622/image/coupang/common/no_img_1000_1000.png'\"                                                     width=\"100%\" alt=\"\"/>                                                                                                                                        </div>                                                                    </div>                                                            <div class=\"type-IMAGE_NO_SPACE\">                                                                            <div class=\"subType-IMAGE\">                                                                                                                                                <img class=\"lazy-img\" src=\"data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==\" data-src=\"//img3c.coupangcdn.com/image/product/content/vendorItem/2017/01/13/48593437/b3209331-c540-45df-aa3a-3edf888c982f.jpg\"                                                     onerror=\"this.src='http://mimg1.coupangcdn.com/thumbnails/remote/622x622/image/coupang/common/no_img_1000_1000.png'\"                                                     width=\"100%\" alt=\"\"/>                                                                                                                                        </div>                                                                    </div>                                                            <div class=\"type-IMAGE_NO_SPACE\">                                                                            <div class=\"subType-IMAGE\">                                                                                                                                                <img class=\"lazy-img\" src=\"data:image/gif;base64,R0lGODlhAQABAAAAACH5BAEKAAEALAAAAAABAAEAAAICTAEAOw==\" data-src=\"//img3c.coupangcdn.com/image/product/content/vendorItem/2017/01/13/48593437/113f2d82-919b-46b1-9695-b590826a3d7b.jpg\"                                                     onerror=\"this.src='http://mimg1.coupangcdn.com/thumbnails/remote/622x622/image/coupang/common/no_img_1000_1000.png'\"                                                     width=\"100%\" alt=\"\"/>                                                                                                                                        </div>                                                                    </div>                                                    </div>                                    </div>                <div class=\"product-vendor-items__seemore\">                    <div class=\"product-vendor-items__morebtn\"><h4 class=\"product-vendor-items__morebtn_txt\">상품정보 더보기</h4> <span class=\"small-arrow-btn-bottom\"></span></div>                </div>            </div>            </div>''")
//            ->find('.prod-item-attr-name');



        $query = $request->query();
        $this->market = Market::wherecode($query['marketId'])->first();
        $this->url = urldecode($query['url']);
        ob_start();
        passthru("/usr/bin/python3 ".app_path()."/python/crawling.py $this->url");
        $market_data = json_decode(ob_get_clean());

        $crawlClass = new CoupangCrawler($market_data);

        return response()->success($crawlClass->getResult());
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
