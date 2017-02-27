<?php

namespace App\Http\Controllers;

use Abort;
use League\Flysystem\Exception;
use Log;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use GuzzleHttp\Client;

use App\Models\Order;
use App\Models\Option;
use App\Models\Product;

use App\Traits\GetUserModelTrait;

class PaypalPaymentController extends Controller
{
    use GetUserModelTrait;

    public $user;
    public $product;
    public $option;
    public $client;
    public $paypalUrl;
    public $paymentUrl;
    public $accessToken;
    public $secretKey;

    public function __construct(Request $request){
        $this->user = $this->getUserByTokenRequestOrFail($request);

        $this->client = new Client();
        $this->setPlace('sandbox');
        $this->paymentUrl = $this->paypalUrl."/payments/payment";
        $this->accessToken = $this->getAccessToken($request);
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
            Abort::Error('0040','Dismatch Key');
        }
        $decodeResult = json_decode($response);
        $result = $decodeResult->token_type.' '.$decodeResult->access_token;
        return $result;
    }

    public function detail(Request $request){
        $query = $request->query();

        $response = $this->client->request('GET', $this->paymentUrl.'/'.$query['paymentId'] , [
            'headers' => [
                "Content-Type" => "application/json",
                "Authorization" => $this->accessToken,
            ],
        ])->getBody()->getContents();

        $decodeResult = json_decode($response);

        return response()->success($decodeResult);
    }

    public function payment(Request $request){
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
            Abort::Error('0070',$e);
        }

            $items = $request->transactions[0]['item_list']['items'][0];
            $customInfo = json_decode($request->transactions[0]['custom']);

            $this->product = Product::findOrFail($customInfo->productId);
            $this->option = Option::findOrFail($items['sku']);

            $order = new Order([
                "user_id" => $this->user->id,
                "order_status_code" => "0310",
                "country_id" => $customInfo->countryId,
                "market_id" => $this->product->market_id,
                "product_id" => $this->product->id,
                "product_option_id" => $this->option->id,
                "product_price" => $items['price'],
                "product_currency" => $items['currency'],
                "product_quantity" => $items['quantity'],
                "product_weight" => $this->product->weight,
                "product_url" => $this->product->url,
                "product_total_price" => $items['price'] * $items['quantity'],
                "domestic_delivery_price" => $this->product->domestic_delivery_price,
                "domestic_delivery_currency" => $this->product->currency,
                "international_delivery_price" => 123123,
                "international_delivery_currency" => "USD",
                "from_currency_amount" => 1100,
                "from_currency" => "KRW",
                "to_currency_amount" => 1,
            ]);
        if($order->save()){
            return response()->success($decodeResult);
        }else{
            Abort::Error('0050','Can not save Order Data');
        }
    }

    public function execute(Request $request){
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

        return response()->success($decodeResult);
    }
}
