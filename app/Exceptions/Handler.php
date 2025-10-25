<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\Exceptions\ThrottleRequestsException;
use Illuminate\Http\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->reportable(function (Throwable $e)
        {
            //
        });

        $this->reportable(function (NotFoundHttpException $e)
        {
        });

        $this->renderable(function (HttpException $e, $request)
        {
            if ($request->is('api/*'))
            {
                return response()->json(['message' => $e->getMessage()], $e->getStatusCode());
            }

            $message = $e->getMessage();

            if (empty($e->getMessage()))
            {
                if ($e->getStatusCode() === 404)
                    $message = 'Страница не найдена';

                else if (array_search($e->getStatusCode(), [403, 401]))
                    $message = 'Доступ запрещен';
            }

            $view = view('errors.general', ['message' => $message, 'status' => $e->getStatusCode()]);
            $view = $view->render();

            return response($view, $e->getStatusCode());
        });
    }
}
