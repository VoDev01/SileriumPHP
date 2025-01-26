<?php

namespace App\Http\Middleware;

use Closure;
use Gate;
use Illuminate\Http\Request;

class AuthorizeUserRouteMiddleware
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
        if(Gate::allows('access-user'))
            return $next($request);
        else
            abort(401);
    }
}
