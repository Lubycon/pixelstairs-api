<?php

namespace App\Http\Controllers;

use Abort;
use League\Flysystem\Exception;
use Log;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Classes\Currency;
use App\Classes\Ems;

use GuzzleHttp\Client;
use Carbon\Carbon;

use App\Models\Order;
use App\Models\Option;
use App\Models\Product;
use App\Models\Country;

use App\Traits\GetUserModelTrait;

class PaypalPaymentController extends Controller
{
    use GetUserModelTrait;

    public $user;
    public $product;
    public $option;
    public $order;
    public $client;
    public $paypalUrl;
    public $paymentUrl;
    public $accessToken;
    public $secretKey;
    public $currency;
    public $ems;
    public $additionalFee = 3;
    public $internationalDeliveryPrice;

    public function __construct(Request $request){
        $this->user = $this->getUserByTokenRequestOrFail($request);

        $this->client = new Client();
        $this->setPlace('sandbox');
        $this->paymentUrl = $this->paypalUrl."/payments/payment";
        $this->accessToken = $this->getAccessToken($request);
        $this->currency = new Currency();
        $this->ems = new Ems();
    }

    public function setPlace($env){
        if( $env == 'live' ){
            $this->secretKey = env('PAYPAL_SECRET_KEY_LIVE');
            $this->paypalUrl = "https://api.paypal.com/v1";
        }else if( $env == 'sandbox' ){
            $this->secretKey = env('PAYPAL_SECRET_KEY_SANDBOX');
            $this->paypalUrl = "https://api.sandbox.paypal.com/v1";
        }else{
            Abort::Error('0070','Unknown Place');
        }
    }

    public function getAccessToken($request){
        $clientKey = $request->clientKey;
        $secretKey = $this->secretKey;
        try{
            $response = $this->client->request('POST', $this->paypalUrl."/oauth2/token" , [
                'auth' => [$clientKey, $secretKey],
                'headers' => [
                    "Content-Type" => "application/x-www-form-urlencoded",
                ],
                'form_params' => [
                    "grant_type" => "client_credentials"
                ],
            ])->getBody()->getContents();
        }catch(\Exception $e){
            $this->exceptionCatch($e);
        }
        $decodeResult = json_decode($response);
        $result = $decodeResult->token_type.' '.$decodeResult->access_token;
        return $result;
    }

    public function detail(Request $request){
        $query = $request->query();
        try{
            $response = $this->client->request('GET', $this->paymentUrl.'/'.$query['paymentId'] , [
                'headers' => [
                    "Content-Type" => "application/json",
                    "Authorization" => $this->accessToken,
                ],
            ])->getBody()->getContents();
        }catch(\Exception $e){
            $this->exceptionCatch($e);
        }
        $decodeResult = json_decode($response);
        $items = $decodeResult->transactions[0]->item_list->items[0];
        $this->option = Option::findOrFail($items->sku);
        $this->product = $this->option->product;

        $items->thumbnailUrl = $this->product->getImageObject($this->product);
        $items->marketName = $this->product->market->getTranslateResultByLanguage($this->product->market->translateName);
        $items->options = $this->product->getOptionTranslate();
        $items->optionKeys = $this->product->getTranslateResultByLanguage($this->product->getOptionKey());

        return response()->success($decodeResult);
    }

    public function payment(Request $request){
        $items = $request->transactions[0]['item_list']['items'][0];
        $shippingAddress = $request->transactions[0]['item_list']['shipping_address'];
        $this->option = Option::findOrFail($items['sku']);
        $this->product = $this->option->product;
        $country = Country::where('alpha2Code','=',$shippingAddress['country_code'])->firstOrFail();
        $this->option->canBuyAble(); // check we have buy able stock
        $this->validPrice($request);// cross check price
        if( !$this->product->isSelling() ) Abort::Error('0054','This item has ended sales');

        try{
            $response = $this->client->request('POST', $this->paymentUrl , [
                'headers' => [
                    "Content-Type" => "application/json",
                    "Authorization" => $this->accessToken,
                ],
                'json' => [
                    "intent" => "sale",
                    "redirect_urls" => $request->redirect_urls,
                    "payer" => [
                        "payment_method" => "paypal",
                    ],
                    "transactions" => $request->transactions,
                ]
            ])->getBody()->getContents();
            $decodeResult = json_decode($response);
        }catch(\Exception $e){
            $this->exceptionCatch($e);
        }

        $this->order = new Order([
            "user_id" => $this->user->id,
            "order_number" => date("YmdH").mt_rand(1000000,9999999),
            "order_status_code" => "0310",
            "recipient_name" => $shippingAddress['recipient_name'],
            "recipient_phone" => $shippingAddress['phone'],
            "country_id" => $country['id'],
            "state" => isset($shippingAddress['state']) ? $shippingAddress['state'] : null,
            "city" => $shippingAddress['city'],
            "address1" => $shippingAddress['line1'],
            "address2" => isset($shippingAddress['line2']) ? $shippingAddress['line2']  : null,
            "post_code" => $shippingAddress['postal_code'],
            "market_id" => $this->product->market_id,
            "product_id" => $this->product->id,
            "product_option_id" => $this->option->id,
            "product_price" => $items['price'],
            "product_currency" => $items['currency'],
            "product_quantity" => $items['quantity'],
            "product_weight" => $this->product->weight,
            "product_url" => $this->product->url,
            "product_total_price" => $decodeResult->transactions[0]->amount->details->subtotal,
            "domestic_delivery_price" => $this->product->domestic_delivery_price,
            "domestic_delivery_currency" => $this->product->currency,
            "international_delivery_price" => $this->internationalDeliveryPrice,
            "international_delivery_currency" => "USD",
            "purchasing_agency_fee" => $decodeResult->transactions[0]->amount->details->handling_fee,
            "from_currency_amount" => $this->currency->getExchangeRate("USD",$this->product->currency),
            "from_currency" => $this->product->currency,
            "to_currency_amount" => 1,
            "to_currency" => "USD",
            "payment_company" => "paypal",
            "payment_id" => $decodeResult->id,
            "payment_create_time" => Carbon::now()->toDateTimeString(),
            "payment_price" => $decodeResult->transactions[0]->amount->total,
            "payment_currency" => $decodeResult->transactions[0]->amount->currency,
            "payment_state" => $decodeResult->state,
        ]);
        if($this->order->save()){
            $this->option->stock = $this->option->stock - $items['quantity'];
            $this->option->save();
            return response()->success($decodeResult);
        }else{
            Abort::Error('0050','Can not save Order Data');
        }
    }

    public function execute(Request $request){
        $this->order = Order::wherepayment_id($request->paymentId)->firstOrFail();
        if( $this->order->order_status_code == '0319' ) Abort::Error('0059');
        if( $this->order->order_status_code != '0310' ) Abort::Error('0040');
        if( !$this->order->product->isSelling() ) Abort::Error('0054','This item has ended sales');

        try{
            $response = $this->client->request('POST', $this->paymentUrl.'/'.$request->paymentId.'/execute' , [
                'headers' => [
                    "Content-Type" => "application/json",
                    "Authorization" => $this->accessToken,
                ],
                'json' => [
                    "payer_id" => $request->PayerID,
                ]
            ])->getBody()->getContents();
            $decodeResult = json_decode($response);
        }catch(\Exception $e){
            $this->exceptionCatch($e);
        }

        $this->order->order_status_code = "0312";
        $this->order->payment_user_id = $decodeResult->payer->payer_info->payer_id;
        $this->order->payment_state = $decodeResult->state;
        $this->order->payment_execute_time = Carbon::now()->toDateTimeString();

        if($this->order->save()){
            return response()->success($decodeResult);
        }else{
            Abort::Error('0050','Can not save Order Data');
        }
        return response()->success($decodeResult);
    }

    public function validPrice(Request $request){
        $transaction = $request->transactions[0];
        $totalPrice = $this->currency->exchangeToUsd(
            (double)$transaction['amount']['total'],
            $transaction['amount']['currency']);
        $itemPrice = $this->currency->exchangeToUsd(
            (double)$this->option['price'] * (int)$transaction['item_list']['items'][0]['quantity'],
            $this->product['currency']);
        $domesticDeliveryPrice = $this->currency->exchangeToUsd(
            (double)$this->product['domestic_delivery_price'],
            $this->product['currency']);
        $internationalDeliveryPrice = $this->currency->exchangeToUsd(
            (double)$this->getInternationalDeliveryPrice('FR',$this->product['weight']*(int)$transaction['item_list']['items'][0]['quantity']),
            'KRW'
        );
        $calcPrice = (double)($itemPrice + $domesticDeliveryPrice + $internationalDeliveryPrice);
        $calcPriceAddFee = $calcPrice + ( $calcPrice * (double)("0.0".$this->additionalFee) );
        if( abs($totalPrice - $calcPriceAddFee) > 1 )
        Abort::Error('0040','Invalid price server calc = '.$calcPriceAddFee.'USD');
    }

    public function expire(Request $request){
        $this->order = Order::wherepayment_id($request->paymentId)->firstOrFail();
        if( $this->order->order_status_code != '0310' ) Abort::Error('0060');

        $this->order->returnStock();

        return response()->success($this->order);
    }

    public function exceptionCatch($e){
        $responseBody = json_decode( $e->getResponse()->getBody()->getContents() );
        $errorMsg = (array)($responseBody);
//        $errorMsg = isset( $responseBody->message )
//            ? $responseBody->message
//            : $responseBody->error_description;
        switch( $e->getCode() ){
            case 400 : $errorCode = '0040'; break;
            case 401 : $errorCode = '0042'; break;
            case 402 : $errorCode = '0042'; break;
            case 403 : $errorCode = '0057'; break;
            case 404 : $errorCode = '0044'; break;
            default : $errorCode = '0070';
        }
        Abort::Error($errorCode,$errorMsg);
    }

    public function getInternationalDeliveryPrice($country_alpha2_code,$weight){
        $this->internationalDeliveryPrice = $this->ems->request($country_alpha2_code,$weight);
        return $this->internationalDeliveryPrice;
    }
}
