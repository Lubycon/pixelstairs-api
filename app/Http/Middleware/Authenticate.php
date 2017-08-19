<?php

namespace App\Http\Middleware;

use Log;
use Closure;
use Auth;
use Illuminate\Contracts\Auth\Guard;

use App\Models\User;

class Authenticate
{
    /**
     * The Guard implementation.
     *
     * @var Guard
     */
    protected $auth;
    protected $access_token;
    protected $user_id;
    protected $user;

    /**
     * Create a new middleware instance.
     *
     * @param  Guard  $auth
     * @return void
     */
    public function __construct(Guard $auth)
    {
        $this->auth = $auth;
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
                    $this->user_id = substr($this->access_token, 31);
                    $this->user = User::
                        where('id',$this->user_id)
                        ->where('token', $this->access_token)
                        ->firstOrFail();
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
