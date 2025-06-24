<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class AuthorizeAdminApiMiddleware
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
        $user = User::where('email', $request->header('php-auth-user'))->get()->first();
        $auth = $request->header('php-auth-pw');
        if(!$auth === $user->password)
            abort(404, 'Wrong password.');
        $response = Gate::forUser($user)->inspect('access-admin');
        if ($response->allowed())
        {
            return $next($request);
        }
        else
        {
            abort(404);
        }
    }
}
