<?php

namespace App\Services\SearchForms\Base;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class SearchFormValidateResponse
{
    public function __construct(protected bool $shouldValidate = true)
    {
    }

    protected function validate(?array $data, string $responseName, ?string $redirect, ?string $notFoundMessage)
    {
        if (!$this->shouldValidate)
            return;
        try
        {
            if (empty($data))
            {
                throw new NotFoundHttpException($notFoundMessage ?? 'Запрашиваемый ресурс не найден');
            }

            Cache::put($responseName, $data, env('CACHE_TTL'));

            if (isset($redirect))
            {
                $routes = Route::getRoutes();

                if ($routes->hasNamedRoute($redirect))
                    return redirect()->route($redirect);
                else
                {
                    $routes->match(Request::create($redirect));
                    return redirect($redirect);
                }
            }

            return response()->json([$responseName => $data]);
        }
        catch (HttpException $e)
        {
            Cache::delete($responseName);
            if (empty($redirect))
                return response()->json([$responseName => $e->getMessage()], $e->getStatusCode());
            else
            {
                return redirect()->back()->withErrors([$responseName => $e->getMessage()]);
            }
        }
    }
}
