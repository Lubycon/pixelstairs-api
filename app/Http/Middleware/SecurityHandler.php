<?php

namespace App\Http\Middleware;

use Closure;

use Barryvdh\Cors\Stack\CorsService;

use Illuminate\Http\Request;

use Abort;
use Log;

class SecurityHandler
{
    protected $cors;
    protected $request;

    public function __construct(CorsService $cors)
	{
		$this->cors = $cors;
	}

	public function handle($request, Closure $next)
	{

		if (! $this->cors->isCorsRequest($request)) {
			return $next($request);
		}

		if ( ! $this->cors->isActualRequestAllowed($request)) {
			Abort::Error('0043','Check Origin');
		}

         if( !$this->isOptionMethod($request) ){
//             if ( $this->devPassKey($request) !== $this->devPassValue() ) {
//                 Abort::Error('0043','Check Dev Pass');
//             }
             if ( !$this->apiUrlVersionCheck($request) || !$this->apiVersionCheck($request) ) {
                 Abort::Error('0073','Check Current API version');
             }
             if ( $this->isProvision() ) {
//                 if ( !$this->checkProvisionFront($request) ) {
//                     Abort::Error('0073');
//                 }
             }
             if ( !$this->requiredHeaderCheck($request) ) {
                 Abort::Error('0047');
             }
         }

		$response = $next($request);

        if ($this->cors->isPreflightRequest($request)) {
            $preflight = $this->cors->handlePreflightRequest($request);
            $response->headers->add($preflight->headers->all());
        }

		return $this->cors->addActualRequestHeaders($response, $request);
	}

    protected function isOptionMethod($request){
        return $request->method() === 'OPTIONS';
    }
    protected function devPassKey($request){
        return $request->header(env('DEV_SERVER_KEY'));
    }
    protected function devPassValue(){
        return env('DEV_SERVER_VALUE');
    }
    protected function apiUrlVersionCheck($request){
        return $request->segment(1) == env('API_URL_VERSION');
    }
    protected function apiVersionCheck($request){
        return $request->header('X-mitty-version') == env('API_VERSION');
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
