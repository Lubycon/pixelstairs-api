<?php

namespace App\Http\Middleware;

use Log;
use Closure;
use Auth;
use Abort;

use Tymon\JWTAuth\Facades\JWTAuth;

use App\Models\User;
use App\Models\AccessToken;

class Authenticate
{
    protected $auth;
    protected $authHeaderName = 'x-pixelstairs-token';
    protected $user_id;
    protected $user;

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
        if( $this->isOptionMethod($request) === false ) {
            $token = is_null( app('request')->header($this->authHeaderName))
                ? app('request')->header($this->authHeaderName)
                :  app('request')->header('Authorization');
            if( !is_null($token) ){
                if (!JWTAuth::parseToken()->authenticate())  return Abort::Error('0043',"Check Token");
            }
        }
        return $next($request);
    }

    protected function isOptionMethod($request){
        return $request->method() === 'OPTIONS';
    }
}
