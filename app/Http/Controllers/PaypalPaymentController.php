<?php

namespace App\Http\Controllers;

use Abort;
use Log;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use GuzzleHttp\Client;

class PaypalPaymentController extends Controller
{
    public $client;
    public $paypalUrl;
    public $paymentUrl;
    public $accessToken;
    public $secretKey;

    public function __construct(Request $request){
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

        return response()->success($decodeResult);
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
