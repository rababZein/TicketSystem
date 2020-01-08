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
use Illuminate\Auth\AuthenticationException;

use App\Mail\ExceptionOccured;
use Illuminate\Support\Facades\Log;
use Mail;
use Symfony\Component\Debug\ExceptionHandler as SymfonyExceptionHandler;
use Symfony\Component\Debug\Exception\FlattenException;

use App\Exceptions\ItemNotCreatedException;
use App\Exceptions\ItemNotUpdatedException;

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
        $enableEmailExceptions = config('exceptions.emailExceptionEnabled');

        if ($enableEmailExceptions === "") {
            $enableEmailExceptions = config('exceptions.emailExceptionEnabledDefault');
        }

        if (($exception instanceof ItemNotCreatedException || $exception instanceof ItemNotUpdatedException) && $enableEmailExceptions && $this->shouldReport($exception) && $exception->getFrom() == 'E-Mail-Inbox') {
            $this->sendEmail($exception);
        }

        parent::report($exception);
    }

    /**
     * Sends an email upon exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function sendEmail(Exception $exception)
    {
        try {
// dd($exception->getErrorMessage());
            $e = FlattenException::create($exception);
            $handler = new SymfonyExceptionHandler();
            $html = $handler->getHtml($e);

            Mail::send(new ExceptionOccured($exception->getTitle(), $exception->getType(), $exception->getErrorMessage()));

        } catch (Exception $exception) {

            Log::error($exception);

        }
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
        // override the API error handling to return a proper JSON.
        if ($exception instanceof AuthenticationException && $request->isJson()) {
            return response()->json([
                'status' => false,
                'message' => 'Token expired',
                'type' => 'AuthenticationException',
                ]
                , 401); //bad request
        } elseif ($exception instanceof NotFoundHttpException && $request->isJson()) {
            return response()->json([
                'status' => false,
                'message' => 'Route not found',
                'type' => 'NotFoundHttpException',
                ]
                , 404);
        } elseif ($exception instanceof ValidationException && $request->isJson()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation Errors',
                'type' => 'ValidationException',
                'errors' => $exception->errors()]
                , 400);
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
