<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use GuzzleHttp\Client;
use Log;

use App\Models\MarketCategory;
use App\Models\MarketDivision;
use App\Models\MarketSector;
use App\Models\MarketDetail;
use DB;


class insertSectorController extends Controller
{
    public $client;
    public $division;
    public $currentDivision;
    public $success;
    public $log;

    public function __construct(){
        // $this->client = new Client();
        // $this->division = Division::all();
        // $this->log = [];
        // $this->success = [];
        //
        // DB::table('sectors')->truncate();
        // DB::table('details')->truncate();
    }

    public function categoryOrder(){
        $division = MarketDivision::all();
        foreach ($division as $key => $value) {
            if ( !$value->category['is_active'] ) {
                Log::info($value->category['is_active']);
                $value->is_active = false;
                $value->save();
            }
        }
    }
    public function divisionActive(){
        $division = MarketDivision::whereis_active(true)->get();
        $result=[];
        foreach ($division as $key => $value) {
            $result[] = array(
                'id' => $value->id,
                'category_id' => $value->category['name'],
                'name' => $value->name,
                'is_active' => '',
            );
        }
        return json_encode($result);
    }

    public function check(){

        foreach ($this->division as $index => $content) {
            $this->setCurrentDivision($content);
            $category_id = $this->currentDivision->data_number;
            $response = $this->requsetBy11st($category_id);
            $responseXml = $this->getXmlOnBody($response);
            if ( !$this->checkError($responseXml) ) {
                $category_data = $this->xmlToJson($responseXml);
                if ( isset($category_data['SubCategory']['Category']) ) {
                    foreach ($category_data['SubCategory']['Category'] as $index => $content) {
                        $sector = array(
                            "market_id" => 1,
                            "category_id" => $this->currentDivision->category_id,
                            "division_id" => $this->currentDivision->data_number,
                            "name" => isset($content['CategoryName']) ? $content['CategoryName'] : 'unknown',
                            "data_number" => isset($content['CategoryCode']) ? $content['CategoryCode'] : 'unknown',
                        );
                        $this->insertSector($sector);
                        $this->success[] = $sector;

                        if ( isset($content['SubCategory']) ) {
                            foreach ($content['SubCategory']['Category'] as $index => $detail) {
                                $detail = array(
                                    "market_id" => 1,
                                    "category_id" => $this->currentDivision->category_id,
                                    "division_id" => $this->currentDivision->data_number,
                                    "sector_id" => $content['CategoryCode'],
                                    "name" => isset($detail['CategoryName']) ? $detail['CategoryName'] : 'unknown',
                                    "data_number" => isset($detail['CategoryCode']) ? $detail['CategoryCode'] : 'unknown',
                                );
                                $this->insertDetail($detail);
                                $this->success[] = $detail;
                            }
                        }
                    }
                }
            }
        }

        return response()->success([
            'success' => $this->success,
            'log' => $this->log,
        ]);
    }

    public function insertSector($sector){
        MarketSector::insert($sector);
    }
    public function insertDetail($detail){
        MarketDetail::insert($detail);
    }
    public function xmlToJson($xml){
        $json = json_encode($xml);
        $array = json_decode($json,TRUE);

        return $array;
    }
    public function setCurrentDivision($content){
        $this->currentDivision = $content;
    }

    public function requsetBy11st($category_id){
        $response = $this->client->request('GET', 'http://openapi.11st.co.kr/openapi/OpenApiService.tmall', [
            'query' => [
                'key' => '079b465d19c823b1582f605532755f3c',
                'apiCode' => 'CategoryInfo',
                'categoryCode' => $category_id,
                'option' => 'SubCategory'
            ]
        ])->getBody()->getContents();

        return $response;
    }
    public function getXmlOnBody($response){
        return simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA);
    }
    public function checkError($responseXml){
        $check = isset($responseXml->ErrorCode);

        if ($check) {
            $this->log[] = (object)array(
                "name" => $this->currentDivision->name,
                "data_number" => $this->currentDivision->data_number,
            );
        }
        return $check;
    }
}
