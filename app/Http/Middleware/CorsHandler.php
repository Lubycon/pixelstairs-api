<?php

namespace App\Http\Middleware;

use Closure;

use Barryvdh\Cors\Util\OriginMatcher;
use Barryvdh\Cors\Stack\CorsService;

use Illuminate\Http\Request;

use Abort;
use Log;

class CorsHandler
{
    protected $cors;
    protected $options;
    protected $request;

    public function __construct(CorsService $cors)
	{
		$this->cors = $cors;
		$this->options = config('cors');
	}

	public function handle(Request $request, Closure $next)
	{

		if (! $this->cors->isCorsRequest($request)) {
			return $next($request);
		}

		$this->options = $request->segments()[0] === 'admin'
            ? $this->options['adminAllowedOrigins']
            : $this->options['allowedOrigins'];

		if ( ! $this->__checkOrigin($request,$this->options)) {
			Abort::Error('0043','Check Origin');
		}

		$response = $next($request);

        if ($this->cors->isPreflightRequest($request)) {
            $preflight = $this->cors->handlePreflightRequest($request);
            $response->headers->add($preflight->headers->all());
        }

		return $this->cors->addActualRequestHeaders($response, $request);
	}


	// Barryvdh\Cors\Stack\CorsService.php
    private function __checkOrigin(Request $request,$originList) {
        $origin = $request->headers->get('Origin');

        foreach ($originList as $allowedOrign) {
            if ( $allowedOrign === '*' || OriginMatcher::matches($allowedOrign, $origin) )
                return true;
        }
        return false;
    }
}
