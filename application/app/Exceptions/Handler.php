<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];
    
    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];
    
    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception $exception
     *
     * @return void
     * @throws \Exception
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }
    
    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception $exception
     *
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        if (!$request->expectsJson()) {
            return parent::render($request, $exception);
        }
        
        $response = [
            'data' => [],
            'status' => 'failed',
        ];
        
        if (config('app.debug')) {
            $response['debug'] = [
                'exception' => get_class($exception),
                'code'      => $exception->getCode(),
                'error'     => $exception->getMessage(),
                'trace'     => $exception->getTrace(),
            ];
        }
        
        if ($exception instanceof ModelNotFoundException) {
            $response['message'] = 'The requested record could not be found';
            // $response['status_code'] = 404;
    
            return response()->json($response, 404);
        } elseif ($exception instanceof NotFoundHttpException) {
            $response['message'] = 'The requested endpoint does not exist';
            // $response['status_code'] = 400;
    
            return response()->json($response, 400);
        }
        
        
        return parent::render($request, $exception);
    }
    
    /**
     * @param \Illuminate\Http\Request $request
     * @param \Illuminate\Auth\AuthenticationException $exception
     *
     * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\RedirectResponse|\Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return $request->expectsJson()
            ? response()->json([
                'data'        => [],
                'message'     => $exception->getMessage(),
            ], 401)
            : redirect()->guest($exception->redirectTo() ?? route('login'));
    }
}
