<?php
//
//namespace App\Http\Controllers;
//
//use Illuminate\Http\Request;
//
//use App\Http\Requests;
//use App\Http\Controllers\Controller;
//
//use App\Models\Product;
//use App\Models\Market;
//use App\Models\Category;
//use App\Models\Division;
//use App\Models\Status;
//use App\Models\Brand;
//use App\Models\Option;
//use App\Models\Order;
//use App\Models\Sku;
//use GuzzleHttp\Client;
//
//use Abort;
//use Log;
//
//use App\Traits\OptionControllTraits;
//
//use App\Http\Requests\Order\OrderPostRequest;
//use App\Http\Requests\Order\OrderUpdateRequest;
//
//class HaitaoController extends Controller
//{
//    use OptionControllTraits;
//
//    public $client;
//    public $language;
//
//    public function __construct(){
//        $this->client = new Client();
//    }
//
//
//    /**
//     * @SWG\Get(
//     *     path="/haitao/product/{haitao_product_id}",
//     *     summary="Get Product Detail",
//     *     description="Get Product Detail via Haitao Product ID",
//     *     produces={"application/json"},
//     *     tags={"Product"},
//     *     @SWG\Parameter(
//     *         name="X-mitty-language",
//     *         default="zh",
//     *         in="header",
//     *         description="Translate language",
//     *         required=true,
//     *         type="string"
//     *     ),
//     *     @SWG\Parameter(
//     *         name="X-mitty-version",
//     *         default="1.0.0",
//     *         in="header",
//     *         description="App version",
//     *         required=true,
//     *         type="string"
//     *     ),
//     *     @SWG\Parameter(
//     *         name="haitao_product_id",
//     *         default="101577081",
//     *         in="path",
//     *         description="product's data you want item id",
//     *         required=true,
//     *         type="integer"
//     *     ),
//     *     @SWG\Response(
//     *         response=200,
//     *         description="successful operation",
//     *     ),
//     *     @SWG\Response(
//     *         response="400",
//     *         description="Unexpected data value",
//     *     ),
//     *     @SWG\Response(
//     *         response="403",
//     *         description="ended product sales",
//     *     ),
//     *     @SWG\Response(
//     *         response="404",
//     *         description="Not found product",
//     *     ),
//     *     @SWG\Response(
//     *         response="411",
//     *         description="require header not exist",
//     *     ),
//     *     @SWG\Response(
//     *         response="500",
//     *         description="Mitty Server Error",
//     *     )
//     * )
//     */
//
//    public function productDetailGet(Request $request,$haitao_product_id){
//        $this->language = $request->header('X-mitty-language');
//        $product = Product::wherehaitao_product_id($haitao_product_id)->firstOrFail();
//
//        if( $product->product_status_code != '0301' ) Abort::Error('0043',"Ended sale product");
//
//        $options = $product->getProvisionOption($product["unit"]);
//
//        $response = (object)array(
//            "mittyProductId" => $product["id"],
//            "marketProductId" => $product["market_product_id"],
//            "haitaoProductId" => $product["haitao_product_id"],
//            "market" => $product->market->getTranslateResultByLanguage($product->market->translateName,$this->language),
//            "category" => $product->category->getTranslateResultByLanguage($product->category->translateName,$this->language),
//            "division" => $product->division->getTranslateResultByLanguage($product->division->translateName,$this->language),
//            "section" => $product->getTranslateResultByLanguage($product->getSections(),$this->language),
//            "title" => $product->getTranslateResultByLanguage($product->translateName,$this->language),
//            "brand" => $product->brand->getTranslateResultByLanguage($product->brand->translateName,$this->language),
//            "description" => $product->getTranslateResultByLanguage($product->translateDescription,$this->language),
//            "weight" => $product["weight"],
//            "weightUnit" => 'g',
//            "price" => $product["original_price"],
//            "lowerPrice" => $product["lower_price"],
//            "priceUnit" => $product["unit"],
//            "delivery_fee" => $product["domestic_delivery_price"],
//            "manufacturer" => $product->manufacturer->country['name'],
//            "thumbnailUrl" => $product->image->url,
//            "url" => $product["url"],
//            "seller" => $product->getSeller(),
//            "gender" => $product->gender->getTranslateResultByLanguage($product->gender->translateName,$this->language),
//            "status" => $product->status->getTranslateResultByLanguage($product->status->translateName,$this->language),
//            "startDate" => $product["start_date"],
//            "endDate" => $product["end_date"],
//            "optionCollection" => $product->getOptionCollection($options,$this->language),
//            "skuLists" => $options,
//        );
//        return response()->success($response);
//    }
//
//    /**
//     * @SWG\Post(
//     *     path="To haitao API",
//     *     summary="Upload Product to 1Haitao",
//     *     description="Upload product via using haitao api ( options array has 0~1000 index )<br/>After upload product, return created your product id via this API",
//     *     produces={"application/json"},
//     *     tags={"Product"},
//     *     @SWG\Parameter(
//     *          name="body",
//     *          in="body",
//     *          required=true,
//     *          @SWG\Schema(
//     *              required={"product_id"},
//     *              @SWG\Property(property="mittyProductId",type="string",default="600"),
//     *              @SWG\Property(property="marketProductId",type="string",default="321785312"),
//     *              @SWG\Property(property="market",type="string",default="11st"),
//     *              @SWG\Property(property="category",type="string",default="化妆品"),
//     *              @SWG\Property(property="division",type="string",default="基于化妆品"),
//     *              @SWG\Property(property="sector",type="string",default="露"),
//     *              @SWG\Property(property="title",type="string",default="雪花秀展示样品（雪花奶油可以辅音声母辅音液）"),
//     *              @SWG\Property(property="brand",type="string",default="N cosmetic"),
//     *              @SWG\Property(property="description",type="string",default="一个女人真正的魅力不言的装饰皮肤表面。翻翻觉醒一颗平常心的全过程走出来完成我的不可动摇的美，尽管时间！"),
//     *              @SWG\Property(property="weight",type="int",default="1"),
//     *              @SWG\Property(property="price",type="int",default="40000"),
//     *              @SWG\Property(property="stock",type="int",default="300"),
//     *              @SWG\Property(property="thumbnailUrl",type="string",default="http://i.011st.com/pd/16/2/4/7/9/9/2/WCzDh/1232247992_B.jpg"),
//     *              @SWG\Property(property="url",type="string",default="http://www.11st.co.kr/product/SellerProductDetail.tmall?method=getSellerProductDetail&prdNo=1232247992&trTypeCd=PW02&trCtgrNo=1002031#ui_option_layer1"),
//     *              @SWG\Property(property="status",type="string",default="产品销售收入"),
//     *              @SWG\Property(property="startDate",type="datetime",default="2017/01/02 00:00:00"),
//     *              @SWG\Property(property="endDate",type="datetime",default="2017/05/02 00:00:00"),
//     *              @SWG\Property(
//     *                  property="options",
//     *                  type="array",
//     *                  @SWG\Items(
//     *                      type="object",
//     *                      @SWG\Property(property="sku", type="string", default="MK0100CT4DV8ST146PD108786628ID"),
//     *                      @SWG\Property(property="name", type="string", default="01 面膜1张,8.申尹祚精华4毫升20瓶+弹性5毫升20狗"),
//     *                      @SWG\Property(property="price", type="string", default="41000"),
//     *                  ),
//     *              ),
//     *          ),
//     *     ),
//     *     @SWG\Response(
//     *         response=200,
//     *         description="return with created your product id",
//     *     )
//     * )
//     */
//
//    public function productStore(Request $request){
//        // in Traits
//    }
//
//    /**
//     * @SWG\Post(
//     *     path="/haitao/order",
//     *     summary="Push Order Data",
//     *     description="Push order data to mitty",
//     *     produces={"application/json"},
//     *     tags={"Order"},
//     *     @SWG\Parameter(
//     *         name="X-mitty-language",
//     *         default="zh",
//     *         in="header",
//     *         description="Translate language",
//     *         required=true,
//     *         type="string"
//     *     ),
//     *     @SWG\Parameter(
//     *         name="X-mitty-version",
//     *         default="1.0.0",
//     *         in="header",
//     *         description="App version",
//     *         required=true,
//     *         type="string"
//     *     ),
//     *     @SWG\Parameter(
//     *          name="body",
//     *          in="body",
//     *          required=true,
//     *          @SWG\Schema(
//     *              required={"product_id"},
//     *              @SWG\Property(property="haitaoOrderId",type="string",default="31247853"),
//     *              @SWG\Property(property="haitaoUserId",type="string",default="12341"),
//     *              @SWG\Property(property="quantity",type="string",default="5"),
//     *              @SWG\Property(property="orderDate",type="datetime",default="2017-10-27 12:50:12"),
//     *              @SWG\Property(property="sku",type="string",default="MK0100CT4DV8ST146PD108786628ID"),
//     *          ),
//     *     ),
//     *     @SWG\Response(
//     *         response=200,
//     *         description="successful operation",
//     *     ),
//     *     @SWG\Response(
//     *         response=400,
//     *         description="400 error",
//     *     ),
//     *     @SWG\Response(
//     *         response=404,
//     *         description="Can not found Product via SKU",
//     *     ),
//     *     @SWG\Response(
//     *         response=424,
//     *         description="Failed Dependency",
//     *     )
//     * )
//     */
//
//    public function orderStore(OrderPostRequest $request){
//
//
//        $order = new Order;
//        $findOption = Option::wheresku($request['sku'])->firstOrFail();
//
//        $order->haitao_order_id = $request['haitaoOrderId'];
//        $order->haitao_user_id = $request['haitaoUserId'];
//        $order->quantity = $request['quantity'];
//        $order->product_id = $findOption['product_id'];
//        $order->sku = $findOption['sku'];
//        $order->order_date = $request['orderDate'];
//        $order->order_status_code = '0313';
//
//        if(!$order->save()) Abort::Error('0040','Check Request');
//
//        return response()->success($order);
//    }
//    public function orderPut(OrderUpdateRequest $request,$haitao_order_id){
//        return response()->success();
//    }
//}
