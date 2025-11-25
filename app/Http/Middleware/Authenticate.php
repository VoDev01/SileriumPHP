<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Authenticate extends Middleware
{
    
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (!$request->expectsJson()) {
            if(preg_match("/seller\/.+$/", $request->getUri()))
            {
                return route('seller.login');
            }
            else if($request->is('api/v1*'))
            {
                return route('login', ['api' => 'api']);
            }
            // else if($request->is('admin*'))
            // {
            //     abort(404);
            // }
            else
                return route('login');
        }
    }
}
