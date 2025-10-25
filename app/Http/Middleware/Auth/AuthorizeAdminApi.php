<?php

namespace App\Http\Middleware\Auth;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AuthorizeAdminApi
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
        $user = $request->user('api');

        if(!isset($user))
            throw new NotFoundHttpException;
            
        $response = Gate::allows('accessAdmin', $user);
        
        if ($response)
        {
            return $next($request);
        }
        else
        {
            throw new AccessDeniedHttpException;
        }
    }
}
