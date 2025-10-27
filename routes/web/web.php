<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\BannedController;
use App\Http\Controllers\FallbackController;
use App\Http\Controllers\Formatting\PdfFormatterController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::withoutMiddleware('auth.refresh.token')->group(function ()
{
    Route::get('/media/{file}', function (string $file, Request $request)
    {
        if (!App::environment('testing'))
        {
            if (!is_file(storage_path(env('APP_MEDIA_PATH')) . $file))
                throw new NotFoundHttpException;

            foreach (explode(', ', env('APP_RESTRICTED_MEDIA_DIR')) as $dir)
            {
                if (strpos($file, storage_path(env('APP_MEDIA_PATH') . '/' . $dir)) && !Auth::check())
                    throw new NotFoundHttpException;
            }
        }

        $fileName = basename($file);
        $lastModified = filemtime(storage_path(env('APP_MEDIA_PATH')) . $file);
        $etag = md5_file(storage_path(env('APP_MEDIA_PATH')) . $file);

        if ($request->hasHeader('Is-Modified-Since') || $request->hasHeader('If-None-Match'))
        {
            if ($request->header('Is-Modified-Since') === $lastModified || trim($request->header('If-None-Match')) === $etag)
            {
                return response(status: 304, headers: [
                    'Cache-Control' => 'public, no-cache, must-revalidate, max-age=2592000',
                    'ETag' => $etag,
                    'Last-Modified' => $lastModified
                ]);
            }
        }

        return response(headers: [
            'X-Sendfile' => storage_path(env('APP_MEDIA_PATH')) . $file,
            'Cache-Control' => 'public, no-cache, must-revalidate, max-age=2592000',
            'Last-Modified' => $lastModified,
            'Content-Type' => 'application/octet-stream',
            'Content-Disposition' => "attachment; filename=\"$fileName\"",
            'ETag' => $etag
        ]);
    })->where('file', '(.*)')->middleware('cache.media.headers');

    Route::get('/', [HomeController::class, 'index'])->middleware(['banned'])->name('home');

    Route::get('/banned', [BannedController::class, 'banned'])->name('banned')->withoutMiddleware('banned');

    Route::controller(PdfFormatterController::class)->prefix('format')->middleware(['auth', 'banned'])->group(function ()
    {
        Route::post('pdf', 'formatPDF')->name('format.pdf');
    });

    Route::fallback(FallbackController::class);
});
