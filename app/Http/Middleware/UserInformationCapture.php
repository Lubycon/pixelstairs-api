<?php

namespace App\Http\Middleware;

use Closure;
use Jenssegers\Agent\Agent;

class UserInformationCapture
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $ip = $request->header("X-Forwarded-For");
        if ($ip == null || strlen($ip) == 0) {
            $ip = $request->header("Proxy-Client-ip");
        }
        if ($ip == null || strlen($ip) == 0) {
            $ip = $request->header("WL-Proxy-Client-ip");
        }
        if ($ip == null || strlen($ip) == 0) {
            $ip = $request->header("HTTP_CLIENT_ip");
        }
        if ($ip == null || strlen($ip) == 0) {
            $ip = $request->header("HTTP_X_FORWARDED_FOR");
        }
        if ($ip == null || strlen($ip) == 0) {
            $ip = $request->ip();
        }

        $location = \Location::get($ip);

        $agent = new Agent();
        $language = $agent->languages();
        $type = 'desktop'; // default
        $typeCode = 'w'; // default
        if ($agent->isDesktop()) {
            $type = 'desktop';
            $typeCode = 'w';
        } else if ($agent->isMobile()) {
            $type = 'mobile';
            $typeCode = 'm';
        } else if ($agent->isTablet()) {
            $type = 'tablet';
            $typeCode = 't';
        }
        $device = [
            'type'    => $type,
            'typeCode'=> $typeCode,
            'device'  => $agent->device(),
            'os'      => $agent->platform(),
            'browser' => $agent->browser()
        ];

        $request->clientInfo = [
            "ip"       => $ip,
            "location" => $location,
            "language" => $language,
            "device"   => $device
        ];

        return $next($request);
    }
}
