<?php

namespace App\Classes;

use Storage;
use Abort;
use Log;

use GuzzleHttp\Client;
use League\Flysystem\Exception;

class Ems
{
    private $client;
    private $result;
    private $url = "http://eship.epost.go.kr/api.EmsTotProcCmd.ems";

    public function __construct(){
        $this->client = new Client();
    }
    public function request($countryCode,$totalWeight){
        try{
            $response = $this->client->request('GET', $this->url.
                "?regkey=".env('EMS_KEY')."&premiumcd=31&countrycd=$countryCode&totweight=$totalWeight&boyn=N&boprc=0&em_ee=em" , [
            ])->getBody()->getContents();
        }catch(\Exception $e){
            Abort::Error('0070',$e->getMessage());
        }
        $this->result = $this->getXmlOnBody($response);

        Log::info( (array)$this->result );

        return trim($this->result->EmsTotProcCmd->emsTotProc[0]);
    }
    private function getXmlOnBody($response){
        return simplexml_load_string($response, 'SimpleXMLElement', LIBXML_NOCDATA);
    }
}