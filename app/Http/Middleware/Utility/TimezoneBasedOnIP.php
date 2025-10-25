<?php

namespace App\Http\Middleware\Utility;

use Closure;
use Illuminate\Http\Request\Throttle;

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
        if(env('APP_ENV') == 'development' || env('APP_ENV') == 'testing')
        {
            return $next($request);
        }
        else
        {
            $country = geoip_country_code_by_name($request->ip());
            date_default_timezone_set(geoip_time_zone_by_country_and_region($country));
            return $next($request);
        }
    }
}
