<?php

namespace App\Http\Middleware;

use Log;
use Closure;
use Auth;
use Abort;

use App\Models\User;
use App\Models\AccessToken;

class Authenticate
{
    protected $auth;
    protected $access_token;
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
            try {
                $this->access_token = app('request')->header("x-pixel-token");
                if( !is_null($this->access_token) ){
                    $this->user_id = substr($this->access_token, AccessToken::$randomLength+1);
                    $tokenValidation = AccessToken::validToken($this->user_id,$this->access_token);
                    if( $tokenValidation === false ) Abort::Error('0043',"Check Token");
                    Auth::onceUsingId($this->user_id);
                }
            } catch (\Exception $e) {
            }
        }
        return $next($request);
    }

    protected function isOptionMethod($request){
        return $request->method() === 'OPTIONS';
    }
}
