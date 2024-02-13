<?php

namespace App\Exceptions;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
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
     */
    public function register(): void
    {
        $this->renderable(function (NotFoundHttpException $e, $request) {

        });
    }

    public function render($request, Throwable $e)
    {
        if ($request->ajax() || $request->wantsJson()) {

            $e = $this->prepareException($e);

            if ($e instanceof ModelNotFoundException) {
                $e = new NotFoundHttpException($e->getMessage(), $e);
            } elseif ($e instanceof NotFoundHttpException) {
                return response()->json(['error' => 'Resource not found'], 404);
            } elseif ($e instanceof \Illuminate\Auth\AuthenticationException) {
                return $this->unauthenticated($request, $e);
            } elseif ($e instanceof \Illuminate\Validation\ValidationException) {
                return $this->convertValidationExceptionToResponse($e, $request);
            }

            $response = [];

            $response['error'] = $e->getMessage();

            $statusCode = method_exists($e, 'getStatusCode')
                ? $e->getStatusCode()
                : 500;
            return response()->json($response, $statusCode);
        }
        return parent::render($request, $e);
    }
}
