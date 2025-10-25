<?php

namespace App\Http\Middleware\Auth;

use App\Actions\CheckBannedApiUser;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

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
        $banned = false;
        $response = null;

        if($request->is(env('APP_URL') . 'user/login*') ||
        $request->is(env('APP_URL') . 'user/register*') ||
        $request->is(env('APP_URL') . 'user/sign_in*'))
        {
            $response = $next($request);
        }

        if ($request->user('api'))
        {
            $banned = CheckBannedApiUser::check($request->user('api'));
        }
        else if (Auth::check())
        {
            $banned = Gate::allows('banned', Auth::user());
        }

        if ($banned === false)
        {
            if(isset($response))
                return $response;
            
            return $next($request);
        }
        else
            return redirect()->route('banned');
    }
}
