<?php
//namespace App\Traits;
//
//use GuzzleHttp\Client;
//use Log;
//use App\Models\Market;
//use App\Models\Category;
//use App\Models\Division;
//use App\Models\Status;
//use App\Models\Brand;
//use App\Models\Option;
//
//trait HaitaoRequestTraits{
//
//    public $client;
//
//    public function __construct(){
//        $this->client = new Client();
//    }
//
//    public function productSale($product){
//        $api_path = 'mitty.api/v1/products';
//        $response = $this->client->request('POST', $api_path, [
//            'form_params' => [
////                "mittyProductId" => $product["id"],
//            ]
//        ])->getBody()->getContents();
//        return $response;
//    }
//}
//?>