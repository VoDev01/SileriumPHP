<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Models\UserApiKey;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class AuthorizeApiKeyMiddleware
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
        $userApiKey = UserApiKey::where('user_id', $user->ulid)->get()->first();
        if(null !== $request->header('API-Key'))
        {
            if($request->header('API-Key') == $userApiKey->api_key)
                return $next($request);
            else
                abort(403, 'API Key is invalid.');
        }
        else
        {
            abort(403, 'API Key is missing.');
        }
    }
}
