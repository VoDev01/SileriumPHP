<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use Illuminate\Http\Request;

class SetMediaCacheHeaders
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
        if (stripos($request->url(), 'media/') === false)
            return $next($request);

        $lastModified = filemtime(env('APP_MEDIA_PATH') . $request->file);
        $etag = md5_file(env('APP_MEDIA_PATH') . $request->file);

        $request->headers->add([
            'Is-Modified-Since' => $lastModified,
            'If-Non-Match' => $etag
        ]);

        return $next($request);
    }
}
