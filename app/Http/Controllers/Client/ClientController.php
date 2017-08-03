<?php

namespace App\Http\Controllers\Client;

// Global
use Log;
use Abort;

// Models
// Require
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;


class ClientController extends Controller
{

    /**
     * @SWG\Get(
     *   path="/client",
     *   summary="get client info",
     *   operationId="client",
     *   tags={"/Client"},
     *   @SWG\Response(response=200, description="successful operation")
     * )
     */
    protected function info(Request $request)
    {
        $clientInfo = $request->clientInfo;
        $result = [
            "ip"       => $clientInfo['ip'],
            "location" => [
                "country" => $clientInfo['location']['countryName'],
                "region"  => $clientInfo['location']['regionName'],
                "city"    => $clientInfo['location']['cityName'],
            ],
            "language" => $clientInfo['language'][0],
            "device"   => $clientInfo['device'],
        ];
        return response()->success($result);
    }

}
