<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Hash;
use Illuminate\Support\Str;

class CheckBannedUser
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
        if (null !== Auth::user())
        {
            $response = Gate::inspect('banned');
            if ($response->allowed())
                return $next($request);
            else
                return redirect()->route('banned');
        }
        else if (null !== $request->header('php-auth-user'))
        {
            $user = User::where('email', $request->header('php-auth-user'))->get()->first();
            $auth = $request->header('php-auth-pw');
            if (!$auth === $user->password)
                abort(404, 'Wrong password.');
            $response = Gate::forUser($user)->inspect('banned');
            if ($response->allowed())
            {
                return $next($request);
            }
            else
            {
                abort(403, 'Your profile has been banned and you are unable to access this api resource. Check your api profile for further information.');
            }
        }
        return $next($request);
    }
}
