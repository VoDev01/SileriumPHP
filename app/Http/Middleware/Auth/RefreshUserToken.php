<?php

namespace App\Http\Middleware\Auth;

use Closure;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RefreshUserToken
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
        $user = User::where('id', Auth::id())->get()->first();

        if (!$user)
        {
            throw new NotFoundHttpException('Пользователь не аутентифицирован');
        }

        if (Carbon::now()->diffInMinutes($user->expiresIn, false) <= 0)
        {
            $user->expiresIn = Carbon::now()->addMinutes(env('APP_USER_REFRESH'));
            $user->token = Str::random(rand(16, 64));
            $user->save();
            $request->headers->set('Token', $user->token);
        }

        return $next($request);
    }
}
