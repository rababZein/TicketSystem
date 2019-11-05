<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Exceptions\ItemNotFoundException;

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
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        // dd(($exception instanceof ValidationException));
        // override the API error handling to return a proper JSON.
        if ($exception instanceof NotFoundHttpException) {
            return response()->json([
                'status' => false,
                'message' => 'Route not found',
                'type' => 'NotFoundHttpException',
                ]
                , 404); //bad request
        } elseif ($exception instanceof ValidationException) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Errors',
                'type' => 'ValidationException',
                'errors' => $exception->errors()]
                , 400); //bad request
        } elseif ($exception instanceof AuthorizationException) {
            return response()->json([
                'status' => false,
                'message' => 'This action is unauthorized',
                'type' => 'AuthorizationException',
                'data' => auth()->user()]
                , 403); //unauthoized
        } elseif ($exception instanceof UnauthorizedException) {
            return response()->json([
                'status' => false,
                'message' => 'This action is unauthorized',
                'type' => 'UnauthorizedException',
                'data' => auth()->user()]
                , 403); //unauthoized
        } elseif ($exception instanceof ModelNotFoundException) {
            throw new ItemNotFoundException($request->url());
        }
        return parent::render($request, $exception);
    }
}
