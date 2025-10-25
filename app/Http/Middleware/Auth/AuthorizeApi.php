<?php

namespace App\Http\Middleware\Auth;

use Closure;
use App\Models\User;
use App\Models\APIUser;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class AuthorizeApi
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
            if ($request->header('API-Key') === null)
            {
                throw new AccessDeniedHttpException('API Key is invalid.');
            }

            if ($request->header('API-Secret') === null)
            {
                throw new AccessDeniedHttpException('API Secret is missing.');
            }

            $apiUser = APIUser::where('api_key', $request->header('API-Key'))->get()->first();

            if ($apiUser === null)
            {
                throw new AccessDeniedHttpException('API Key is invalid.');
            }

            if (Hash::check($request->header('API-Secret'), $apiUser->secret))
                return $next($request);
            else
            {
                throw new AccessDeniedHttpException('API Secret is invalid.');
            }
        }
        else
        {
            $token = $request->header('Token');
            $user = User::where('token', $token)->get()->first();
        
            if ($user === null)
            {
                throw new AccessDeniedHttpException('Пользователь не аутентифицирован');
            }

            return $next($request);
        }
    }
}
