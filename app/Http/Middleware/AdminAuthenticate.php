<?php

namespace App\Http\Middleware;

use Closure;

use Illuminate\Http\Request;

use Abort;
use Log;

use App\Models\User;

class AdminAuthenticate
{
	public function handle(Request $request, Closure $next)
	{
        if ( User::isAdmin() ) {
            return $next($request);
        }else{
            Abort::Error('0043','You are not Admin');
        }
	}
}
