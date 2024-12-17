<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TimezoneBasedOnIP
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $region = geoip_region_by_name($request->ip());
        date_default_timezone_set(geoip_time_zone_by_country_and_region($region['country_code'], $region['region']));
        return $next($request);
    }
}
