<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class RefreshUserTokenMiddleware
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
        try
        {
            $user = User::where('id', Auth::id())->get()->first();

            if (!$user)
            {
                throw new NotFoundHttpException;
            }

            if (Carbon::now()->diffInMinutes($user->expiresIn, false) <= 0)
            {
                $user->expiresIn = Carbon::now()->addMinutes(15);
                $user->token = Str::random(rand(16, 256));
                $user->save();
            }

            return $next($request);
        }
        catch (HttpException $e)
        {
            abort($e->getStatusCode(), $e->getMessage());
        }
    }
}
