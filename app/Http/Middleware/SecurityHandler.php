<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Http\Request;

use Abort;
use Log;

class SecurityHandler
{
    protected $request;

	public function handle(Request $request, Closure $next)
	{
         if( !$this->isOptionMethod($request) ){
//             if ( !$this->apiUrlVersionCheck($request) || !$this->apiVersionCheck($request) ) {
//                 Abort::Error('0073','Check Current API version');
//             }
//             if ( !$this->requiredHeaderCheck($request) ) {
//                 Abort::Error('0047');
//             }
         }

        return $next($request);
	}

    protected function isOptionMethod($request){
        return $request->method() === 'OPTIONS';
    }
    protected function apiUrlVersionCheck($request){
        // return $request->segment(1) == env('API_URL_VERSION');
        return true;
    }
    protected function devPassKey($request){
        return $request->header(env('DEV_SERVER_KEY'));
    }
    protected function devPassValue(){
        return env('DEV_SERVER_VALUE');
    }
    protected function apiVersionCheck($request){
        return $request->header('X-pixel-version') == env('API_VERSION');
    }
    protected function isProvision(){
        return env('APP_ENV') === 'provision';
    }
    protected function checkProvisionFront($request){
        return $request->origin === env('APP_PROVISION_FRONT_URL');
    }
    protected function requiredHeaderCheck($request){
        $requiredHeader = config('cors.requiredHeader');
        $requestHeader = $request->header();
        foreach ($requiredHeader as $key => $value) {
            if( !array_key_exists($value,$requestHeader) )return false;
        }
        return true;
    }
}
