<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class VerifyApiSecret
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
        if($request->header('API-Secret') == null)
            abort(422, 'Missing API secret.');
        else
        {
            if(Auth::user()->tokens->first()->id == $request->header('API-Secret'))
                return $next($request);
            else
                abort(422, 'API Secret is invalid.');
        }
    }
}
