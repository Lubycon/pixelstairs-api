<?php

namespace App\Http\Middleware;

use App\Http\Requests\Request;
use Log;
use Closure;
use Auth;
use Abort;

use Tymon\JWTAuth\Facades\JWTAuth;

use App\Models\User;
use App\Models\AccessToken;

class Authenticate
{
    protected $request;
    protected $authHeaderName = 'x-pixel-token';
    protected $token;
    protected $headerName;

    public function __construct()
    {
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $this->setRequest($request);
        if( $this->isOptionMethod() === false ) {
            $this->setHeaderName();
            if( !is_null($this->token) ){
                $this->setHeaderForLegacy();
                if (!JWTAuth::setRequest($this->request)->parseToken()->authenticate()){
                    return Abort::Error('0043',"Check Token");
                }
            }
        }
        return $next($this->request);
    }

    protected function setRequest(\Illuminate\Http\Request $request){
        return $this->request = $request;
    }

    protected function setHeaderName(){
        if( !is_null( $this->request->header($this->authHeaderName)) ){
            $this->token = $this->request->header($this->authHeaderName);
            $this->headerName = $this->authHeaderName;
        }else if(!is_null( $this->request->header('Authorization')) ){
            $this->token = $this->request->header('Authorization');
            $this->headerName = 'Authorization';
        }
        return true;
    }

    protected function setHeaderForLegacy(){
        // Deprecated!
        // X-pixel-token conversion function
        if( $this->headerName === $this->authHeaderName ){
            $this->request->headers->set('Authorization', 'bearer '.$this->token);
        }
        return true;
    }

    protected function isOptionMethod(){
        return $this->request->method() === 'OPTIONS';
    }
}
