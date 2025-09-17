<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\APIUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Crypt;

class AuthorizeApiMiddleware
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
        if (!empty($request->header('API-Key')))
        {
            $apiUser = APIUser::where('api_key', Crypt::encrypt($request->header('API-Key')))->get()->first();

            if ($apiUser === null)
                abort(403, 'API Key is invalid.');

            if ($request->header('API-Secret') === null)
                abort(403, 'API Secret is missing.');

            if (Hash::check($request->header('API-Secret'), $apiUser->secret))
                return $next($request);
            else
                abort(403, 'API Secret is invalid.');
        }
        else
        {
            if ($request->user() !== null)
                abort(404);

            return $next($request);
        }
    }
}
