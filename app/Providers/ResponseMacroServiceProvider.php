<?php

namespace App\Providers;

use App\Http\Controllers\ErrorController;
use Illuminate\Support\ServiceProvider;
use Illuminate\Contracts\Routing\ResponseFactory;
use Request;
use Log;

class ResponseMacroServiceProvider extends ServiceProvider
{
    public function boot(ResponseFactory $factory)
    {
        $factory->macro('success', function ($data=null) use ($factory) {
            $response = response()->json([
                'status' => (object)array(
                    'code' => '0000',
                    'msg' => "success",
                    "devMsg" => ''
                ),
                'result' => isset($data) ? $data : null
            ]);

            return $response;
        });
        $factory->macro('error', function ($data) use ($factory) {
            $code = $data['code'];
            $config = config('error.'.$data['code']);
            $msg = $config->msg;
            $httpCode = isset($data['httpCode']) ? $data['httpCode'] : $config->httpCode;
            $devMsg = isset($data['devMsg']) ? $data['devMsg'] : '';

            $request = Request::instance();
            $response = response()->json([
                'status' => (object)array(
                    'code' => $code,
                    'msg' => $msg,
                    "devMsg" => $devMsg
                ),
                'result' => null
            ],$httpCode);

            // correct barryvdh on error heading
            if ($request->is(env('API_VERSION').'/*')) {
                app('Barryvdh\Cors\Stack\CorsService')->addActualRequestHeaders($response, $request);
            }

            return $response;
        });
    }
    public function register()
    {
        //
    }
}
